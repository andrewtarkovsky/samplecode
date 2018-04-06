<?php

namespace Samplecode\Api\Classes;

class SampleLaravel
{
  // ...

  protected function getPackaged()
  {
    $ticketTypes = collect($this->ticketTypes['Tickets'])->groupBy('AreaCategoryCode');
    $locale = Input::get('locale');

    foreach ($ticketTypes as $areaCode => $areaTicketTypes) {
      $this->response[$areaCode] = $areaTicketTypes->filter(function ($type) use ($areaCode) {
        return $type['AreaCategoryCode'] === $areaCode
        && $type['IsPackageTicket'] === ((int)$areaCode !== self::LOVE_AREA_CODE);
      })->sortBy(function ($type, $key) {
        return $type['DisplaySequence'];
      })->map(function ($type) use ($locale) {
        $sortedPackageTickets = collect($type['PackageContent']['Tickets'])->sortBy(function ($ticket, $key) {
          return (int)$ticket['TicketTypeCode'];
        });

        $packeContent = '';

        foreach ($sortedPackageTickets as $ticket) {
          for ($i = 0; $i < $ticket['Quantity']; $i++) {
            $packeContent .= (int)$ticket['TicketTypeCode'] . '.';
          }
        }

        $type['PackageContent'] = rtrim($packeContent, '.');

        return collect($type)
          ->only([
            'TicketTypeCode',
            'PriceInCents',
            'PackageContent',
            'LoyaltyRecognitionId'
          ]);
      })->values()->toArray();
    }
  }

  // ...
}

?>