//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

/**
 * @mixin \IPS\core\modules\front\system\ajax
 */
class hook3 extends _HOOK_CLASS_
{

    /**
     * Fetch SHOUTcast Stats
     */
    protected function fetchRadioStats()
    {
        // Get cached SHOUTcast data
        $shoutcast = \IPS\Data\Store::i()->shoutcastStats ?? [];

        // Return JSON response
        if (!empty($shoutcast)) {
            \IPS\Output::i()->json($shoutcast);
        } else {
            \IPS\Output::i()->json(['error' => 'No data available'], 404);
        }
    }
}
