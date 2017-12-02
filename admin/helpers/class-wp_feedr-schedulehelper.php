<?php

	class WP_Feedr_Schedulehelper {

		private $periods;

		public function __construct(){
			$this->periods = [
				'daily',
				'twicedaily',
				'hourly'
			];
		}


		public function schedule_event($hook, $period){

			if (!in_array($period, $this->periods))
			{
				$period = $this->periods[0];
			}

			wp_schedule_event( current_time( 'timestamp' ), $period, $hook );
			return true;
		}

		public function remove_scheduled_event($hook){
				wp_clear_scheduled_hook( $hook );
			return true;
		}

		public function get_schedule($hook, $args = ''){
			if ($args == '')
			{
				return wp_get_schedule($hook);
			}
			else
			{
				return wp_get_schedule($hook, $args);
			}
		}

		public function get_next_scheduled($hook, $args = ''){
			if ($args == '')
			{
				return wp_next_scheduled( $hook );
			}
			else
			{
				return wp_next_scheduled( $hook, $args );
			}
		}

	}

?>