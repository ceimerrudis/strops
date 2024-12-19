<?php

//Pārbauda vai konkrētais id eksistē norādītajā tabulā
//$entryType ir jābūt EntryTypes enumeratora vērtībai.
function IdExistsInTable(int $entryType, int $id): bool
{   
    $model = GetModelFromEnum($entryType);
    if($model === false)
        return false;
    return $model::where('id', $id)->exists();
}
