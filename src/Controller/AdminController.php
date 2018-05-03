<?php
/**
 * Created by PhpStorm.
 * User: laiah
 * Date: 04/04/18
 * Time: 10:52
 */

namespace Controller;

use Model\AdminManager;
use Model\ArticleManager;
use Model\ArtistManager;
use Model\AdminBenevolManager;
use Model\BenevolManager;
use Model\StyleManager;

/**
 *  Class AdminController
 */
class AdminController extends AbstractController
{
    /*
     * Display login page
     * @return string
     */
    public function login()
    {
        $errors = [];
        if ($_POST) {

            $username = $_POST['username'];
            $password = $_POST['password'];

            try {
                $login = new AdminManager($username, $password);
                if ($login->isLoginCorrect($username, $password)) {
                    $_SESSION['username'] = $username;
                } else {
                    $errors[] = 'Le nom d\'utilisateur et/ou le mot de passe est incorrect.';
                }
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }
        }
        if (!isset($_SESSION['username'])) {
            return $this->twig->render('Admin/login.html.twig', ['errors' => $errors]);
        } else {
            header('Location: /admin');
        }
    }

    public function admin()
    {
        if (!isset($_SESSION['username'])) {
            header('Location: /login');
        } else {
            return $this->twig->render('Admin/logged.html.twig');
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /login');

    }

     public function adminBenevol()
    {
        $contentManager = new ArticleManager();
        $benevolManager = new BenevolManager();


        if (isset($_GET['delete'])){
            $delete = $benevolManager->deleteBenevol($_GET['delete']);
        }
        $benevol = $benevolManager->selectNameId();
         if ($_POST) {
            $data = ['title' => $_POST['title'],
                     'content' => $_POST['content']
                    ];
            if (strlen($_POST['picture'])> 0){
                $data['picture'] = '/assets/DBimages/'.$_POST['picture'];
            }
            $contentManager->update(6, $data);
        }
        $content = $contentManager->selectOneById(6);
        return $this->twig->render('Admin/adminBenevol.html.twig', ['content'=>$content, 'benevol'=>$benevol]);

    }

    public function adminArtist()
    {
        $artistManager = new ArtistManager();
        $styleManager = new StyleManager();
        $styles = $styleManager->selectStyle();

        if ($_POST) {
            $data = ['name' => $_POST['name'],
                     'about' => $_POST['about'],
                     'id_style' => $_POST['id_style']];
            if (strlen($_POST['picture']) > 0) {
                $data['picture'] = '/assets/DBimages/'.$_POST['picture'];
            }
            if (isset($_GET['artistSelect'])) {
                $artistManager->update($_GET['artistSelect'], $data);
            } else {
                $artistManager->insert();
            }
        }

        $artists = $artistManager->selectNameId();
        if (isset($_GET['artistSelect'])) {
            $artistId = $artistManager->selectOneById($_GET['artistSelect']);
            return $this->twig->render('Admin/adminArtist.html.twig', ['artists' => $artists, 'artistId' => $artistId, 'styles' => $styles]);
        }
        return $this->twig->render('Admin/adminArtist.html.twig', ['artists' => $artists, 'styles' => $styles]);
    }
}

