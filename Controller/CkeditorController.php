<?php
namespace Smirik\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Exception\IOException;

class CkeditorController extends Controller
{
    /**
     * @Route("/ckeditor_browse_files", name="ckeditor_browse_files")
     * @Secure(roles="ROLE_USER")
     * @Template("SmirikAdminBundle:Ckeditor:image.list.html.twig")
     */
    public function browseAction(Request $request)
    {
        $upload_dir = $this->container->get('kernel')->getRootDir() . '/../web/uploads/images';

        $finder = new Finder();
        $images = $finder->files()->name('/\.png$|\.jpg$|\.jpeg$|\.gif$/')->in($upload_dir);

        /* Example:
        foreach ($images as $image) {
            /** @var \Symfony\Component\Finder\SplFileInfo $image *___/
            echo $image->getFilename() . "<br>";;
        }*/

        return array(
            'base_dir' => '/uploads/images',
            'CKEditorFuncNum' => $request->query->get('CKEditorFuncNum'),
            'images'   => $images
        );
    }

    /**
     * @Route("/ckeditor_upload_file", name="ckeditor_upload_file")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("SmirikAdminBundle:Ckeditor:upload_responce.html.twig")
     */
    public function uploadAction(Request $request)
    {
        /** @var UploadedFile $upload_file */
        $upload_file = $request->files->get('upload');
        if ( null === $upload_file || ! $upload_file->getClientSize()) {
            return new Response('Error file upload');
        }
        $target_filename = time() . '_' . $upload_file->getClientOriginalName();

        $fs = new Filesystem();

        $upload_dir = $this->container->get('kernel')->getRootDir() . '/../web/uploads/images';

        if ( ! $fs->exists($upload_dir)) {
            $fs->mkdir($upload_dir);
            $fs->chmod($upload_dir, 0777);
        }

        $fs->rename($upload_file, $upload_dir . '/' . $target_filename, $overwrite = true);

        return array(
            'image_src' => $request->getBasePath() . '/uploads/images/' . $target_filename,
            'CKEditorFuncNum' => $request->query->get('CKEditorFuncNum'),
        );
    }
}