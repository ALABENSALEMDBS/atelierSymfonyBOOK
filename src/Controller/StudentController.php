<?php

namespace App\Controller;

use App\Entity\Student;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }





    #[Route('/addData/{name?}', name: 'add_Data')]
    public function save(EntityManagerInterface $e, HttpFoundationRequest $request): Response
    {

    
        if(isset($_POST['name']) && isset($_POST['prenom']) && isset($_POST['d_n']) && isset($_POST['class']) && isset($_POST['moyenne']))
            {
               // $name = $request->get('name');
               // echo $name;

        

        $name = $_POST['name'];
        $prenom = $_POST['prenom'];
        $date_naissance =$_POST['d_n'];
        $class = $_POST['class'];
        $moyenne = $_POST['moyenne'];


        // Convertir la chaîne de date au format Y-m-d
        $dateNaissanceObj = \DateTime::createFromFormat('Y-m-d', $date_naissance);

        // Vérifiez si la conversion a réussi
        if (!$dateNaissanceObj) {
            throw new \Exception("Invalid date format");
        }


        $student = new Student();
        $student->setName($name);
        $student->setPrenom($prenom);
        $student->setDateNaissance($dateNaissanceObj);
        $student->setClasse($class);
        $student->setMoyenne($moyenne);


        $e->persist($student);
        $e->flush();


        return $this->redirectToRoute('app_student');

        }
        #return new Response("<h1> DONE </h1>");
        return $this->render('student/formSave.html.twig');
        
    }




    #[Route('/showData', name: 'show_Data')]
    public function show(EntityManagerInterface $e, ManagerRegistry $repository , HttpFoundationRequest $request): Response
    {
        $registry = $repository->getRepository(Student::class);
        $columns  = $e->getClassMetadata('App\Entity\Student')->getColumnNames();
        $data = $registry->findAll();

        #dd($data);
        

        return $this->render('student/showData.html.twig',[

            'data'=> $data,
            'columns' =>$columns
        ]);

        
    }
}
