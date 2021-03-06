<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Exception;

/**
 * Class DataCounterCountCommand
 * @package App\Command
 */
class DataCounterCountCommand extends Command
{
    protected static $defaultName = 'app:data-counter:count';

    protected function configure()
    {
        $this
            ->setDescription('Counts the data values from files where name is equal to the file path argument.')
            ->addArgument('file_path', InputArgument::REQUIRED, 'Required file path argument.')
            ->addOption('file_name', null, InputOption::VALUE_OPTIONAL, 'Optional file name, if not set - count.txt')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('file_path');

        if ($filePath) {
            $io->note(sprintf('You passed an path argument: %s', $filePath));
        }

        // If no file name, set up the default value
        if ($input->getOption('file_name')) {
            $fileName = $input->getOption('file_name');
        } else {
            $fileName = 'count.txt';
        }

        try {
            $resultSum = $this->getDirectoriesSum([$filePath], 0, $fileName);
        } catch(Exception $exception) {
            return -1;
        }

        $io->success('The sum is: ' . $resultSum);

        return 0;
    }

    /**
     * Get the files value sum
     *
     * Recursively iterate over the directory structure and calculate the sum
     *
     * @param array $directoriesCount
     * @param int $sum
     * @param string $fileName
     * @return int
     */
    private function getDirectoriesSum(array $directoriesCount, int $sum, string $fileName): int {
        foreach ($directoriesCount as $key => $directory) {
            $directoryData = array_diff(scandir($directory), ['.', '..']);
        
            $directorySum = 0;
            $directorySubFoldersCount = [];
            foreach ($directoryData as $index => $file) {
                $fullFilePath = $directory . '/' . $file;
                if(is_dir($fullFilePath)) {
                    $directorySubFoldersCount[] = $fullFilePath;
                    continue;
                }

                if($file === $fileName) {
                    $fileDataValue = file_get_contents($fullFilePath, true);
                    $directorySum += (int)$fileDataValue;
                }
            }

            $sum += $directorySum;
        }

        if(isset($directorySubFoldersCount) && count($directorySubFoldersCount) > 0) {
            return $this->getDirectoriesSum($directorySubFoldersCount, $sum, $fileName);
        }

        return $sum;
    }
}
