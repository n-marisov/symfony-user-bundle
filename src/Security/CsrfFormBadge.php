<?php

namespace Maris\Symfony\User\Security;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

/**
 * Проверяет валидность формы.
 */
class CsrfFormBadge implements BadgeInterface
{

    private FormInterface $form;

    /**
     * @param FormInterface $form
     */
    public function __construct(FormInterface $form)
    {
        $this->form = $form;
    }

    /**
     * @inheritDoc
     */
    public function isResolved(): bool
    {
        return $this->form->isSubmitted() && $this->form->isValid();
    }
}