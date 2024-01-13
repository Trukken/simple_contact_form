<?php

namespace App\Controller;

use App\DTO\ContactFormDTO;
use App\Entity\ContactDTO;
use App\Entity\ContactForm;
use App\Form\ContactFormType;
use App\Repository\ContactFormRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/contact")
 */
class ContactFormController extends AbstractController
{
    /**
     * @Route("/", name="app_contact_form_index", methods={"GET"})
     */
    public function index(ContactFormRepository $contactFormRepository): Response
    {
        return $this->render('contact_form/index.html.twig', [
            'contact_forms' => $contactFormRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_contact_form_new", methods={"GET", "POST"})
     */
    public function new(Request $request,TranslatorInterface $translator, ValidatorInterface $validator, ManagerRegistry $managerRegistry , SerializerInterface $serializer): Response
    {
        $contactForm = new ContactForm();
        $form = $this->createForm(ContactFormType::class, $contactForm);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $contactFormDto = $serializer->deserialize($request->getContent(), ContactFormDTO::class, 'json');
            $errors = $validator->validate($contactFormDto);
            if (count($errors) > 0) {
                return $this->json($errors->get(0)->getMessage(), Response::HTTP_BAD_REQUEST);
            }
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($contactFormDto->toEntity());
            $entityManager->flush();

            return $this->json($translator->trans('contact_form.response.success'), Response::HTTP_CREATED);
        }

        return $this->renderForm('contact_form/new.html.twig', [
            'contact_form' => $contactForm,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_contact_form_show", methods={"GET"})
     */
    public function show(ContactForm $contactForm): Response
    {
        return $this->render('contact_form/show.html.twig', [
            'contact_form' => $contactForm
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_contact_form_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ContactForm $contactForm, ContactFormRepository $contactFormRepository): Response
    {
        $form = $this->createForm(ContactFormType::class, $contactForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormRepository->add($contactForm, true);

            return $this->redirectToRoute('app_contact_form_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact_form/edit.html.twig', [
            'contact_form' => $contactForm,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_contact_form_delete", methods={"POST"})
     */
    public function delete(Request $request, ContactForm $contactForm, ContactFormRepository $contactFormRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contactForm->getId(), $request->request->get('_token'))) {
            $contactFormRepository->remove($contactForm, true);
        }

        return $this->redirectToRoute('app_contact_form_index', [], Response::HTTP_SEE_OTHER);
    }
}
