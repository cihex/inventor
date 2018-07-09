<?php
namespace AdminBundle\Controller;

use Symfony\Component\Form\FormInterface;

/**
 * Class ErrorCatcherAwareTrait
 * @package AdminBundle\Controller
 */
trait ErrorCatcherAwareTrait
{
    /**
     * @param FormInterface $form
     * @param string $name
     * @return \string[]
     */
    public static function getFormErrors(FormInterface $form, $name = ''): array
    {
        $errors = [];
        if ($form instanceof FormInterface) {
            foreach ($form->getErrors() as $key => $error) {
                $errors[$name][] = $error->getMessage();
            }
            foreach ($form->all() as $key => $child) {
                if ($child instanceof FormInterface) {
                    /** @var FormInterface $child */
                    $err = self::getFormErrors($child, $child->getName());
                    if (count($err) > 0) {
                        $errors += $err;
                    }
                }
            }
        }
        return $errors;
    }
}