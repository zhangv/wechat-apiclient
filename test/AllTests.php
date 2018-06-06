<?php
use \PHPUnit\Framework\TestSuite;

class AllTests extends TestSuite {

	public static function suite() {
		$suite = new TestSuite();
		$tests = ['WechatApiClient','apiclient/Member','apiclient/Menu'];
		foreach($tests as $t){
			$filePath = __DIR__ . "/{$t}Test.php";
			require_once($filePath);
			$clz = substr($t,(strpos($t,'/')===false)?0:strpos($t,'/')+1);
			$suite->addTestSuite($clz . 'Test');
		}
		return $suite;
	}
}