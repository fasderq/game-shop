<?php

namespace GameShop\Site\Console;


use Doctrine\DBAL\Connection;
use GameShop\Site\User\Model\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UserCommand
 * @package GameShop\Site\Console
 */
class UserCommand extends Command
{

    protected $connection;

    /**
     * @param Connection $connection
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function configure(): void
    {
        $this->setName('user:create');
        $this->setDescription('create user');

        $this->addArgument('username', InputArgument::REQUIRED, 'The username of the user');
        $this->addArgument('email', InputArgument::REQUIRED, 'The email of the user');
        $this->addArgument('password', InputArgument::REQUIRED, 'The password of the user');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        if ($this->connection instanceof Connection) {

            $query = $this->connection->createQueryBuilder()
                ->select('*')
                ->from('user')
                ->where('is_active = 1')
                ->andWhere('email = :email')->setParameter('email', $input->getArgument('email'));

            if (!$userData = $query->execute()->fetch()) {
                $this->connection->beginTransaction();
                $this->connection->insert(
                    'user',
                    [
                        'name' => $input->getArgument('username'),
                        'email' => $input->getArgument('email'),
                        'password' => $input->getArgument('password'),
                        'is_active' => 1
                    ]
                );

                $lastInsertId = $this->connection->lastInsertId();

                $data = $this->connection->createQueryBuilder()
                    ->select('*')
                    ->from('user')
                    ->where('id = :id')->setParameter('id', $lastInsertId)
                    ->execute()->fetch();
                $output->writeln('<info>User created</info>');
                $this->connection->commit();

                $user = $this->rowToUser($data);

                $table = new Table($output);
                $table->setHeaders(['id', 'name', 'email']);
                $table->setRows(
                    [
                        [$user->getId(), $user->getName(), $user->getEmail()]
                    ]
                );
                $table->render();

            } else {
                $user = $this->rowToUser($userData);
                $output->writeln(sprintf('%s %s %s', 'User with email', $user->getEmail(), 'already exist'));
            }
        }
    }

    /**
     * @param array $row
     * @return User
     */
    protected function rowToUser(array $row): User
    {
        return new User($row['id'], $row['name'], $row['email']);
    }
}
