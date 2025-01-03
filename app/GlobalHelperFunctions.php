<?php
use App\Enums\EntryTypes;

//Šis fails tiek automātiski pievienots vairākās vietās tapēc veic duplikācijas pārbaudi.
function Text($key)
{
    return config("texts.{$key}");
}

//Šīs funkcijas nodrošina iespēju viegli uzglabāt un parādīt paziņojumus
//Paziņojumi var būt dažādi, piemēram "Ievadītās motorstundas ir mazākas par datu bāzē esošajām."
function AddMessage(string $text, string $status): void
{
    $messages = session()->get('messages', []);
    $messages[] = ['text' => $text, 'status' => $status];
    session()->put('messages', $messages);
}

//Iegūst visus paziņojumus bet saglabā tos
function GetMessages(): array
{
    return session()->get('messages', []);
}

//Iegūst visus paziņojumus un izdzēš tos
function DeleteMessages(): array
{
    return session()->pull('messages', []); 
}

//Izdzēš konkrētu ziņu
function DeleteMessage($index): array
{
    $messages = session()->get('messages', []);
    if (isset($messages[$index])) {
        unset($messages[$index]);
        session()->put('messages', array_values($messages));
    }
}

//Šī funkcija pāriet no enumeratora uz modeli
function GetModelFromEnum($entryType)
{
    $modelMap = [
        EntryTypes::USER->value => \App\Models\User::class,
        EntryTypes::VEHICLE->value => \App\Models\Vehicle::class,
        EntryTypes::OBJECT->value => \App\Models\ObjectModel::class,
        EntryTypes::REPORT->value => \App\Models\Report::class,
        EntryTypes::VEHICLE_USE->value => \App\Models\VehicleUse::class,
        EntryTypes::RESERVATION->value => \App\Models\Reservation::class,
        EntryTypes::ERROR->value => \App\Models\Error::class,
    ];

    if (!array_key_exists($entryType, $modelMap)) {
        return false; 
    }

    return $modelMap[$entryType];
}