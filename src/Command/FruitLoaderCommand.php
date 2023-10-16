<?php

namespace App\Command;

use App\Exception\MailerException;
use App\Repository\FruitRepository;
use App\Repository\NutritionRepository;
use App\Service\FruityViceApiService;
use App\Service\MailerService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FruitLoaderCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'fruits:fetch';

    /**
     * @var FruitRepository
     */
    private $fruitRepository;

    /**
     * @var NutritionRepository
     */
    private $nutritionRepository;

    /**
     * @var FruityViceApiService
     */
    private $fruityViceApiService;

    /**
     * @var MailerService
     */
    private $mailerService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param FruitRepository $fruitRepository
     * @param NutritionRepository $nutritionRepository
     * @param FruityViceApiService $fruityViceApiService
     * @param MailerService $mailerService
     * @param LoggerInterface $logger
     */
    public function __construct(
        FruitRepository $fruitRepository,
        NutritionRepository $nutritionRepository,
        FruityViceApiService $fruityViceApiService,
        MailerService $mailerService,
        LoggerInterface $logger
    ) {
        parent::__construct(null);
        $this->fruitRepository = $fruitRepository;
        $this->nutritionRepository = $nutritionRepository;
        $this->fruityViceApiService = $fruityViceApiService;
        $this->mailerService = $mailerService;
        $this->logger = $logger;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('---------------------');
        $output->writeln('Starting Fruit Loader');
        $output->writeln('---------------------');

        $this->fruitRepository->beginTransaction();
        try {
            $output->writeln('Truncating Fruits table.');
            $this->fruitRepository->truncate();

            $output->writeln('Done. Truncating Nutritions table.');
            $this->nutritionRepository->truncate();

            $output->writeln('Done. Fetching list of fruit from fruityvice.com');
            $fruits = $this->fruityViceApiService->fetchFruits();

            $output->writeln('Done. Saving fruits to database.');
            foreach ($fruits as $fruit) {
                $this->fruitRepository->save($fruit);
            }

            $this->fruitRepository->commitTransaction();

            $output->writeln('Done. Attempting to send an email.');
            $this->mailerService->sendEmail();
            $output->writeln('Success.');

        } catch (MailerException $exception) {
            $output->writeln('Error occured while sending an email. Please check the logs for more info.');
            $this->logger->error($exception->getMessage());
        } catch (\Exception $exception) {
            $output->writeln('Error occured while loading data. Please check the logs for more info.');

            $this->logger->error($exception->getMessage());
            $this->fruitRepository->rollBackTransaction();

            return Command::FAILURE;
        }

        $output->writeln('-------------------------------');
        $output->writeln('Fruit data successfully loaded.');
        $output->writeln('-------------------------------');

        return Command::SUCCESS;
    }
}
