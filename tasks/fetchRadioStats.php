<?php
/**
 * @brief		fetchRadioStats Task
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	gjradiostats
 * @since		19 Feb 2025
 */

namespace IPS\pluginTasks;

/* To prevent PHP errors (extending class does not exist) revealing path */
if (!\defined('\IPS\SUITE_UNIQUE_KEY')) {
	header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 403 Forbidden');
	exit;
}

/**
 * fetchRadioStats Task
 */
class _fetchRadioStats extends \IPS\Task
{
	/**
	 * Execute
	 *
	 * If ran successfully, should return anything worth logging. Only log something
	 * worth mentioning (don't log "task ran successfully"). Return NULL (actual NULL, not '' or 0) to not log (which will be most cases).
	 * If an error occurs which means the task could not finish running, throw an \IPS\Task\Exception - do not log an error as a normal log.
	 * Tasks should execute within the time of a normal HTTP request.
	 *
	 * @return	mixed	Message to log or NULL
	 * @throws	\IPS\Task\Exception
	 */
	public function execute()
	{
		// Get SHOUTcast settings
		$ip = \IPS\Settings::i()->gjradiostats_ip;
		$port = \IPS\Settings::i()->gjradiostats_port;
		$debug = \IPS\Settings::i()->gjradiostats_debug;

		// Ensure settings are configured
		if (!$ip || !$port) {
			if ($debug) {
				\IPS\Log::log("SHOUTcast fetch failed: IP & Port not configured.", 'fetchRadioStats');
			}
			return;
		}
		try {
			if ($debug) {
				\IPS\Log::log("SHOUTcast fetch DEBUG: Fetching http://{$ip}:{$port}/stats?json=1", 'fetchRadioStats');
			}
			$url = \IPS\Http\Url::external("http://{$ip}:{$port}/stats?json=1");
			$response = $url->request()->get()->decodeJson();

			if (!empty($response)) {
				// For debugging purposes, we'll generate a GUID
				$response['random'] = uniqid(rand());

				\IPS\Data\Store::i()->shoutcastStats = $response;
				if ($debug) {
					\IPS\Log::log("SHOUTcast fetched: " . json_encode($response, JSON_PRETTY_PRINT), 'fetchRadioStats');
				}
			} else {
				\IPS\Data\Store::i()->shoutcastStats = array(
					'servertitle' => 'Offline',
					'streamstatus' => 0,
					'random' => uniqid(rand())
				);
				if ($debug) {
					\IPS\Log::log("SHOUTcast fetch failed: Empty Response, using {$ip}:{$port}", 'fetchRadioStats');
				}

			}
		} catch (\Exception $e) {
			\IPS\Data\Store::i()->shoutcastStats = array(
				'servertitle' => 'Offline',
				'streamstatus' => 0,
				'random' => uniqid(rand())
			);
			\IPS\Log::log("SHOUTcast fetch failed: " . $e->getMessage(), 'fetchRadioStats');
		}
	}

	/**
	 * Cleanup
	 *
	 * If your task takes longer than 15 minutes to run, this method
	 * will be called before execute(). Use it to clean up anything which
	 * may not have been done
	 *
	 * @return	void
	 */
	public function cleanup()
	{

	}
}