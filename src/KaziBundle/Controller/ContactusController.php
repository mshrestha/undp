<?php

namespace KaziBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Page;
use AppBundle\Form\PageType;

use Symfony\Component\HttpFoundation\Response;

/**
 * Contactus controller.
 *
 * @Route("/contactus")
 */
class ContactusController extends Controller
{

    /**
     * Lists all Page entities.
     *
     * @Route("/", name="contactus")
     * 
     * @Template()
     */
    public function indexAction(Request $request)//annotation removed @Method("GET")
    {
        //for frontend homepage only
        /*$data_name = $request->request->get('data_name');
        $data_address = $request->request->get('data_address');
        $data_email = $request->request->get('data_email');
        $data_phone = $request->request->get('data_phone');
        $data_message = $request->request->get('data_message');
        $data_captcha = $request->request->get('data_captcha');*/

        //$response = array("code" => 1, "success" => false);

        if(isset($data_name)) {//if there is name
            /*echo $_SESSION["code"] . '-' . $data_captcha;
            if ($_SESSION["code"] == $_POST["captcha"]) {
                echo "Form Submitted successfully....!";
                //$response = array("code" => 100, "success" => true);
            } else {
                die("Wrong TEXT Entered");
            }*/
            
            /*$to = "giovanni.congi@one.un.org";
            $subject = "Contact From UNDP Website";
            
            $txt = '<strong>Name:</strong> ' . $data_name . 
                '<br><strong>Address:</strong> ' . $data_address .
                '<br><strong>Email:</strong> ' . $data_email . 
                '<br><strong>Phone:</strong> ' . $data_phone .
                '<br><strong>Message:</strong> ' . $data_message;

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= "From: <info@undp.kazi270.com>" . "\r\n";
            $headers .= "CC: bronwyn.russel@one.un.org" . "\r\n";
            $headers .= "Bcc: manish@kazistudios.com"."\r\n";

            mail($to,$subject,$txt,$headers);*/
        }


        $em = $this->getDoctrine()->getManager();
        $criteria = array('slug'=> 'contactus');//about need to change to contact

        $entity_for_id = $em->getRepository('AppBundle:Page')->findBy($criteria);
        $id = $entity_for_id['0']->getId();

        $entity = $em->getRepository('AppBundle:Page')->find($id);//$id - id with key 'o' error

        //$entities = $em->getRepository('AppBundle:Page')->findAll();
        //print_r($entities);exit();

        return array(
            'entity' => $entity,
            //'response' => $response
        );
    }
}