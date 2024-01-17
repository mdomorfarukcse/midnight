<?php

namespace Pemm\Core;

use Firebase\JWT\JWT as FirebaseJWT;

class Jwt
{
    const KEY = 'Z2tSWJHqWNE2MRqV/EMUcBwjVOnp6hSnH54hNExd86AqHxeXAho51+MIICXAIBAAKBgQDU2kN/IqKCiNwJ//APTCSR/DnFwIDAQABAoGAAU/nIp2ACvX1BUnR64wIOj2Z7I+Y7lvDvhiC0+TuHDcJMY6MVwAPiHS8BAZ1CsVEjB68lMabcF3mtemJ5OOHJJSlF/JMNl8uDEK7sfzGoV9i1GzH7kLnEUDTvjGB+MJRoOfoyPCPldOy5u13ZhcX1FPnSw2+XtxnXKZErXLnzRfHmECQQD1t+QVKnpiwKMiNkUZJ5UKML7XIBG8JtKZGwNFF2cdqepWJ0shMiXnact2irQk9pbjbznvLRd2aISXLimrWOCDAkEA3cJRhCRmoEcrqrieDgqDLbIZAXTtdCNPM310vfX3DPXj/T46w3+IkVf6w/v+eyh9bZhxQkFwZLpkIgO8HLSy3QJAaDRZLMS7ppp7D9Hr42WVimcIhs0A6VdAA7yxu1WwKlBGKpjb6/wQ56xxmbuW1JNPVO5/6++wPi9d4Cxoi94imsBowJAO9up2TtiID1Vwh1XPH6668F7Z23NjdzqPem90UT7/qmlu8AfWkAv6h7GpBe9evdQy0ovh554+J6DhZp1OyhKDZ2WQJBAL08KMnEPo+bLx7yPB4q+qjG0+K9GAQv8QuOZCDCZ24XRRi4zmuLwCuMqZzFXEZpctswvwF1bngySCzSxt6oQZA=';

    public static function generateToken($payload, $key = null): string
    {
        return FirebaseJWT::encode($payload, $key ?? self::KEY);
    }

    public static function decode($token, $key = null): object
    {
        return FirebaseJWT::decode($token, $key ?? self::KEY, ['HS256']);
    }

}
