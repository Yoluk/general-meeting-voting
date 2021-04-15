<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210415165735 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        //Adding Admin user to database
        $factory = $this->container->get('migrations.security.encoder_factory');

        $user = new User();
        $user->setEmail("admin@admin");

        $password = "admin";
        $encoder = $factory->getEncoder($user);
        $pass = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($pass);

        $user->setRoles(["ROLE_ADMIN"]);

        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();
    }

    public function down(Schema $schema) : void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $user = $em->getRepository(User::class)->findOneBy(["email" => "admin@admin"]);

        $em->remove($user);
        $em->flush();
    }
}
