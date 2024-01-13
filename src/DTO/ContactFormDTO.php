<?php

namespace App\DTO;

use App\Entity\ContactForm;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\DataTransformerInterface;

class ContactFormDTO implements DataTransformerInterface
{
    #[Assert\NotBlank(message: 'contact_form.name.not_blank')]
    private ?string $name;

    #[Assert\NotBlank(message: 'contact_form.email.not_blank')]
    #[Assert\Email(message: 'contact_form.email.not_email')]
    private ?string $email;

    #[Assert\NotBlank(message: 'contact_form.message.not_blank')]
    private ?string $message;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }


    public function toEntity(): ContactForm
    {
        $contactForm = new ContactForm();
        $contactForm->setName($this->getName());
        $contactForm->setEmail($this->getEmail());
        $contactForm->setMessage($this->getMessage());

        return $contactForm;
    }

    public function fromEntity(ContactForm $contactForm): ContactFormDTO
    {
        $contactFormDto = new self();
        $contactFormDto->setName($contactForm->getName());
        $contactFormDto->setEmail($contactForm->getEmail());
        $contactFormDto->setMessage($contactForm->getMessage());

        return $contactForm;
    }
    public function transform($value)
    {
        if (empty($value)) {
            return json_decode([]);
        }

        return json_decode($value);
    }

    public function reverseTransform($value)
    {
        if (empty($value)) {
            return json_encode([]);
        }

        return json_encode($value);
    }
}
