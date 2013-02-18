<?php

namespace Smirik\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Smirik\CourseBundle\Model\CourseQuery;
use Smirik\CourseBundle\Model\LessonQuery;
use Smirik\CourseBundle\Model\UserLessonQuery;
use Smirik\CourseBundle\Model\LessonQuizQuery;
use Smirik\QuizBundle\Model\UserQuizQuery;
use Smirik\QuizBundle\Model\QuizQuery;

use Smirik\AdminBundle\Controller\Base\AdminGroupController as BaseController;

class AdminGroupController extends BaseController
{
	/**
	 * @Route("/admin/groups/{id}/stat", name="admin_groups_stat", requirements={"id" = "\d+"})
	 * @Template("SmirikAdminBundle:Admin/Group:stat.html.twig")
	 * @Secure(roles="ROLE_ADMIN")
	 * @ParamConverter("get", class="\FOS\UserBundle\Propel\Group")
	 */
	public function statAction(\FOS\UserBundle\Propel\Group $group)
	{
	    $users = $group->getUsers();
	    $users_ids = array_keys($users->toArray('Id'));
	    
	    $courses = CourseQuery::create()
	        ->find();

	    $lessons = array();
	    foreach ($courses as $course)
	    {
	        $lessons[$course->getId()] = array();
	        $users_lessons[$course->getId()] = array();
	    }
	    
	    $quizes_stat = array();
	    $quizes_num  = array();
	    foreach ($courses as $course)
	    {
	        $id = $course->getId();
	        $lessons[$id] = LessonQuery::create()
	            ->filterByCourseId($id)
	            ->orderBySortableRank()
	            ->find();
	        
	        $lessons_ids = array_keys($lessons[$id]->toArray('Id'));
	        
            $connection = \Propel::getConnection();
	        $query      = 'SELECT ul.id, ul.user_id, ul.course_id, ul.lesson_id, ul.is_passed, ul.is_closed, AVG(ut.mark) AS average FROM users_lessons ul LEFT JOIN users_tasks ut ON (ul.user_id = ut.user_id AND ul.lesson_id = ut.lesson_id)  WHERE ul.user_id IN ('.implode(',', $users_ids).') GROUP BY ul.id;';
            $statement  = $connection->prepare($query);
            $statement->execute();
            
	        foreach ($lessons[$id] as $lesson)
	        {
	            $users_lessons[$id][$lesson->getId()] = array();
	        }
	        while ($res = $statement->fetch(\PDO::FETCH_ASSOC))
	        {
	            $users_lessons[$id][$res['lesson_id']][$res['user_id']] = $res;
	        }
	        
	        $lessons_quizes = LessonQuizQuery::create() 
	            ->select(array('QuizId', 'LessonId'))
	            ->filterByLessonId($lessons_ids)
	            ->find()
	        ;
	        $lessons_quizes_array = $lessons_quizes->toArray();
	        
	        $lessons_quizes_ids = array_keys($lessons_quizes->toArray('LessonId'));
	        $quizes_ids = array_keys($lessons_quizes->toArray('QuizId'));
            // $lessons_quizes_array = array();
            // foreach ($lessons_quizes as $lesson_quiz)
            // {
            //     if (!isset($lessons_quizes_array[$lesson_quiz['LessonId']]))
            //     {
            //         $lessons_quizes_array[$lesson_quiz['LessonId']] = array();
            //     }
            //     $lessons_quizes_array[$lesson_quiz['LessonId']][] = $lesson_quiz['QuizId'];
            // }
            // 
	        $users_quizes = UserQuizQuery::create('uq')
	            ->withColumn('AVG(num_right_answers)', 'average')
	            ->filterByUserId($users_ids)
	            ->filterByQuizId($quizes_ids)
	            ->groupBy('UserId')
	            ->groupBy('QuizId')
	            ->find()
	        ;
	        
	        $quizes_ids = array_keys($users_quizes->toArray('QuizId'));
	        $quizes = QuizQuery::create()
	            ->filterById($quizes_ids)
	            ->find()
	            ->toArray('Id')
	        ;
	        
	        $quizes_stat[$course->getId()] = array();
	        $quizes_num[$course->getId()]  = array();
	        foreach ($users_quizes as $user_quiz)
	        {
	            foreach ($lessons_quizes_array as $obj)
	            {
    	            if (!isset($quizes_stat[$course->getId()][$obj['LessonId']]))
    	            {
    	                $quizes_stat[$course->getId()][$obj['LessonId']] = array();
    	                $quizes_num[$course->getId()][$obj['LessonId']] = array();
    	            }
	                if ($obj['QuizId'] == $user_quiz->getQuizId())
	                {
	                    if (!isset($quizes_stat[$course->getId()][$obj['LessonId']][$user_quiz->getUserId()]))
	                    {
	                        $quizes_stat[$course->getId()][$obj['LessonId']][$user_quiz->getUserId()] = 0.0;
	                        $quizes_num[$course->getId()][$obj['LessonId']][$user_quiz->getUserId()] = 0;
	                    }
    	                $quizes_stat[$course->getId()][$obj['LessonId']][$user_quiz->getUserId()] += (float)$user_quiz->getNumRightAnswers()/$quizes[$user_quiz->getQuizId()]['NumQuestions'];
    	                $quizes_num[$course->getId()][$obj['LessonId']][$user_quiz->getUserId()] += 1;
	                }
	            }
	        }
	        
	    }
	    return array(
	       'group'         => $group,
	       'users'         => $users,
	       'courses'       => $courses,
	       'lessons'       => $lessons,
	       'users_lessons' => $users_lessons,
	       'quizes_stat'   => $quizes_stat,
	       'quizes_num'    => $quizes_num,
	    );
	}

}

