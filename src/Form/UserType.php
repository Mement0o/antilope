<?php

namespace App\Form;

use App\Entity\User;
use App\Security\Voter\UserVoter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User */
        $user = $builder->getData();


        $builder
            ->add('description', null, [
                'help' => 'You can use Markdown to describe yourself.',
            ])
            ->add('paranoia', ChoiceType::class, [
                'choice_loader' => new CallbackChoiceLoader(
                    function() use ($user) {
                        return array_slice(UserVoter::getParanoiaLevels(), 0, $user->getUserClass()->getMaxParanoia() + 1);
                    }
                )
            ])
            ->add('edit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
