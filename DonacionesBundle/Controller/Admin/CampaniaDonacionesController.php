<?php

namespace AhoraMadrid\DonacionesBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AhoraMadrid\AdminBaseBundle\Controller\AdminController as AdminController;
use AhoraMadrid\DonacionesBundle\Entity\CampaniaDonaciones;
use AhoraMadrid\DonacionesBundle\Form\CampaniaDonacionesType;

class CampaniaDonacionesController extends AdminController{
	
	/**
	 * @Route("/apladmin/editar-campania-donaciones/{id}", name="editar_campania_donaciones")
	 */
	public function editarCampania(Request $request, $id){
		//Control de roles
		$response = parent::controlSesion($request, array(parent::ROL_ADMIN));
		if($response != null) return $response;
		
		//Se recoge la campa�a de microcr�ditos
		$em = $this->getDoctrine()->getManager();
		$repoMicro = $em->getRepository('AhoraMadridDonacionesBundle:CampaniaDonaciones');
		$campania = $repoMicro->find($id);
		
		//Se carga el formulario
		$form = $this->createForm(new CampaniaDonacionesType(), $campania);
		$form->handleRequest($request);
		
		if($form->isValid()){
			//Se guarda
			$em->flush();
			
			//Se guarda el mensaje
			$sesion = $this->getRequest()->getSession();
			$sesion->getFlashBag()->add('mensaje', 'La campaña se ha guardado correctamente');
			
			return $this->redirectToRoute('listar_donaciones');
		}
		
		return $this->render('AhoraMadridDonacionesBundle:Admin:editar_campania_donaciones.html.twig', array(
				'form' => $form->createView()
		));
	}
}