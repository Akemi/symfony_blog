<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Datetime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BlogFixture extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        echo "Creating Admin\n";
        $admin = new User();
        $admin
            ->setUsername('Admin')
            ->setMail('admin@admin.de')
            ->setRoles(array('ROLE_ADMIN'));
        $admin->setPassword($this->encoder->encodePassword($admin, '1234'));
        $manager->persist($admin);

        echo "Creating Author1\n";
        $author1 = new User();
        $author1
            ->setUsername('Author1')
            ->setMail('Author1@author.de')
            ->setRoles(array('ROLE_USER'));
        $author1->setPassword($this->encoder->encodePassword($author1, '123456'));
        $manager->persist($author1);

        $dateStart = new DateTime('1.1.2018 00:00:00');
        $dateEnd = new DateTime();

        for ($i = 1; $i < 11; $i++) {
            echo "Creating Admin Post $i\n";
            $post = new Post();

            $image = 'https://via.placeholder.com/600x150/'.$this->randomColor().'/'.$this->randomColor().'.png';
            $content = file_get_contents('http://loripsum.net/api/10/decorate/link/code/headers/medium/ul');
            $post
                ->setAuthor($admin)
                ->setContent($content)
                ->setDate($this->randomDateInRange($dateStart, $dateEnd))
                ->setImage($image)
                ->setTitle('Blog Post '.$i);
            $manager->persist($post);

            for ($j = 1; $j < mt_rand(3,8); $j++) {
                echo "Creating Admin Post Comment $j\n";
                $comment = new Comment();

                $image = 'https://via.placeholder.com/600x150/'.$this->randomColor().'/'.$this->randomColor().'.png';
                $content = file_get_contents('http://loripsum.net/api/2/decorate/link/medium');

                $comment
                    ->setDate($this->randomDateInRange($post->getDate(), $dateEnd))
                    ->setContent($content)
                    ->setName('User'.mt_rand(1,10))
                    ->setPost($post)
                    ->setUrl($image);
                $manager->persist($comment);
            }
        }

        for ($i = 1; $i < 6; $i++) {
            echo "Creating Author1 Post $i\n";
            $post = new Post();

            $image = 'https://via.placeholder.com/600x150/'.$this->randomColor().'/'.$this->randomColor().'.png';
            $content = file_get_contents('http://loripsum.net/api/10/decorate/link/code/headers/medium/ul');
            $post
                ->setAuthor($author1)
                ->setContent($content)
                ->setDate($this->randomDateInRange($dateStart, $dateEnd))
                ->setImage($image)
                ->setTitle('Blog Post '.($i+10));
            $manager->persist($post);

            for ($j = 1; $j < mt_rand(3,8); $j++) {
                echo "Creating Author1 Post Comment $j\n";
                $comment = new Comment();

                $image = 'https://via.placeholder.com/600x150/'.$this->randomColor().'/'.$this->randomColor().'.png';
                $content = file_get_contents('http://loripsum.net/api/2/decorate/link/medium');

                $comment
                    ->setDate($this->randomDateInRange($post->getDate(), $dateEnd))
                    ->setContent($content)
                    ->setName('User'.mt_rand(1,10))
                    ->setPost($post)
                    ->setUrl($image);
                $manager->persist($comment);
            }
        }



        $manager->flush();
    }

    private function randomDateInRange(DateTime $start, DateTime $end) {
        $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
        $randomDate = new DateTime();
        $randomDate->setTimestamp($randomTimestamp);
        return $randomDate;
    }

    private function randomColor() {
        return sprintf('%06X', mt_rand(0, 0xFFFFFF));
    }
}
