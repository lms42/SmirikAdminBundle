<?php

namespace Smirik\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

use Smirik\PropelAdminBundle\Column\Column;
use Smirik\PropelAdminBundle\Column\CollectionColumn;
use Smirik\PropelAdminBundle\Action\Action;
use Smirik\PropelAdminBundle\Action\ObjectAction;
use Smirik\PropelAdminBundle\Action\SingleAction;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Smirik\AdminBundle\Model\UserQuery;

use Smirik\CourseBundle\Model\CourseQuery;
use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\UserCourseQuery;
use Smirik\CourseBundle\Model\UserLessonQuery;
use Smirik\CourseBundle\Model\UserQuestionQuery;
use Smirik\CourseBundle\Model\UserTaskQuery;
use Smirik\CourseBundle\Model\UserTaskAnswerQuery;

use Smirik\QuizBundle\Model\UserQuizQuery;
use Smirik\QuizBundle\Model\QuizQuery;

class AdminUserController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'users';

	public function setup()
	{
		$this->configure(array(
								     array('name' => 'id', 'label' => 'Id', 'type' => 'integer', 'options' => array(
												 'editable' => false,
												 'listable' => true,
												 'sortable' => true,
												 'filterable' => true)),
		                 array('name' => 'username', 'label' => 'Username', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'email', 'label' => 'Email', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'enabled', 'label' => 'Enabled', 'type' => 'boolean', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'groups', 'label' => 'Groups', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'plainpassword', 'label' => 'Password', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => false,
											 'sortable' => true,
											 'filterable' => true)),
		                 ),
		                 array('new' => new SingleAction('New', 'new', 'admin_users_new', true),
											'stat' => new ObjectAction('Statistics', 'stat', 'admin_users_stat', true),
											'edit' => new ObjectAction('Edit', 'edit', 'admin_users_edit', true),
											'delete' => new ObjectAction('Delete', 'delete', 'admin_users_delete', true, true))
		                );
	}

	public function getQuery()
	{
		return \Smirik\AdminBundle\Model\UserQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\AdminBundle\Form\Type\UserType;
	}
	
	public function getObject()
	{
		return new \Smirik\AdminBundle\Model\User;
	}
	
	/**
	 * @Template("SmirikPropelAdminBundle:Admin/Form:edit.html.twig")
	 */
	public function editAction($id)
	{
		$this->setup();
		$this->generateRoutes();
		$this->object = $this->getQuery()->findPk($id);
		if (!$this->object)
		{
			throw $this->createNotFoundException('Not found');
		}
		
		$request = $this->getRequest();
		
		$form = $this->createForm($this->getForm(), $this->object);

		if ('POST' == $request->getMethod())
		{
			$form->bindRequest($request);
			if ($form->isValid())
			{
				$this->object->save();
				
				$um = $this->get('fos_user.user_manager');
				$um->updateUser($this->object);
				
				return $this->redirect($this->generateUrl($this->routes['index']));
			}
		}

		return array(
			'layout' => $this->layout,
			'object' => $this->object,
			'form'   => $form->createView(),
			'columns' => $this->columns,
			'routes' => $this->routes,
		);
	}
	
	/**
	 * @Route("/admin/users/{id}/stat", name="admin_users_stat")
	 * @Template("SmirikAdminBundle:Admin/User:stat.html.twig")
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function statAction($id)
	{
		$user = UserQuery::create()->findPk($id);
		$cm   = $this->get('course.manager');
		$qm   = $this->get('quiz.manager');
		
		$users_courses = UserCourseQuery::create('uc')
			->filterByUserId($user->getId())
			->leftJoin('uc.Course')
			->find();
		
		$courses_lessons = array();
		$tasks_data      = array();
		$questions_data  = array();
		foreach ($users_courses as $user_course)
		{
			$lessons = LessonQuery::create('l')
				->filterByCourseId($user_course->getCourseId())
				->useUserLessonQuery('ul', 'left join')
					->where('(ul.UserId = '.$user->getId().' OR ul.UserId IS NULL)')
				->endUse()
				->joinWith('l.UserLesson', 'left join')
				->orderBySortableRank()
				->find();
			
			$lessons_ids = array_map(function($v){return $v['Id'];}, $lessons->toArray());
			$tasks_data[$user_course->getCourseId()] = $cm->getUserTasksForLesson($lessons_ids, $user->getId());
			$questions_data[$user_course->getCourseId()] = $cm->getUserQuestionsForLesson($lessons_ids, $user->getId());
			$courses_lessons[$user_course->getCourseId()] = $lessons;
		}
		
		$user_quiz = $qm->getQuizesForUser($user->getId());
		
		return array(
			'user'            => $user,
			'courses_lessons' => $courses_lessons,
			'users_courses'   => $users_courses,
			'tasks_data'      => $tasks_data,
			'questions_data'  => $questions_data,
			'user_quizes'     => $user_quiz,
		);
	}
	

}

