<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Coupon\FixedCoupon;
use App\Entity\Coupon\PercentCoupon;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;

#[AsCommand(name: 'app:data-set')]
final class DataSetCommand extends Command
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->createCoupons();

        $this->createProducts();

        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    private function createProducts(): void
    {
        $iphone = new Product(
            Uuid::fromString('01913c04-6b58-7d9a-9fe3-76fb0a4239da'),
            'iphone',
            10000,
        );
        $this->entityManager->persist($iphone);

        $earphones = new Product(
            Uuid::fromString('01913c04-6b92-78b6-ae6c-83e560f818e4'),
            'earphones',
            2000,
        );
        $this->entityManager->persist($earphones);

        $case = new Product(
            Uuid::fromString('01913c04-6b92-78b6-ae6c-83e5612cc353'),
            'case',
            1000,
        );
        $this->entityManager->persist($case);
    }

    private function createCoupons(): void
    {
        $fixCoupon = new FixedCoupon(
            $this->uuidFactory->create(),
            'F100',
            100,
        );
        $this->entityManager->persist($fixCoupon);

        $percentCoupon = new PercentCoupon(
            $this->uuidFactory->create(),
            'P15',
            15,
        );
        $this->entityManager->persist($percentCoupon);
    }
}
