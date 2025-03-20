<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Model\AuctionVehicle;
use App\Service\FeeCalculator;
use App\Form\AuctionVehicleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Enum\VehicleType;

class FeeCalculatorController extends AbstractController
{
    public function __construct(
        private readonly FeeCalculator $feeCalculator
    ) {
    }

    #[Route('/api/vehicles/fees/calculate', name: 'api_vehicle_fees_calculate', methods: ['POST'])]
    public function calculateFees(Request $request): JsonResponse
    {
        $vehicle = new AuctionVehicle(0.0, VehicleType::COMMON);
        $form = $this->createForm(AuctionVehicleType::class, $vehicle);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return new JsonResponse([
                'errors' => (string) $form->getErrors(true, false)
            ], Response::HTTP_BAD_REQUEST);
        }

        // We could have used a query (cqrs), dispatch it through a command bus and get a query response containing the vehicle and the fees
        // for the purpose of this exercise, we will just use the fee calculator service
        $this->feeCalculator->calculateFees($vehicle);

        return new JsonResponse([
            'vehicle' => [
                'basePrice' => $vehicle->basePrice,
                'type' => $vehicle->type->value,
            ],
            'fees' => array_map(
                fn($fee) => [
                    'name' => $fee->name,
                    'amount' => $fee->amount
                ],
                $vehicle->calculatedFees
            ),
            'total' => $vehicle->getTotalPrice()
        ]);
    }
} 