<?php

class MongoStrictTest extends PHPUnit_Framework_TestCase
{

	public function testEmpty()
	{
		$doc = array();
		$encoded = MongoJson::strict($doc);
		$this->assertEquals("{}", $encoded);
	}

	public function testDate()
	{
		date_default_timezone_set("UTC");
		$doc = array("dt" => new MongoDate(1360682361));
		$encoded = MongoJson::strict($doc);
		$this->assertEquals('{"dt":{"$date":1360682361000}}', $encoded);

		$doc = array("dt" => new MongoDate(strtotime("2013-06-11T14:52:48+0000")));
		$encoded = MongoJson::strict($doc);
		$this->assertEquals('{"dt":{"$date":1370962368000}}', $encoded);
	}

	public function testObjectId()
	{		
		$id = new MongoId;
		$doc = array("_id" => $id);
		$encoded = MongoJson::strict($doc);
		$this->assertEquals('{"_id":{"$oid":"'.$id.'"}}', $encoded);
	}

	public function testTimestamp()
	{
		$ts = new MongoTimestamp;
		$doc = array("ts" => $ts);
		$encoded = MongoJson::strict($doc);
		$this->assertEquals('{"ts":{"$timestamp":{"t":'.$ts->sec.',"i":'.$ts->inc.'}}}', $encoded);
	}

	public function testBinData()
	{
		$data = pack("nvc*", 0x1234, 0x5678, 65, 66);
		$bin = new MongoBinData($data, 1);
		$doc = array("binary" => $bin);
		$encoded = MongoJson::strict($doc);
		$this->assertEquals('{"binary":{"$binary":"\u00124xVAB","$type":"1"}}', $encoded);
	}

	public function testRegex()
	{
		$regex = new MongoRegex('/^acme.*corp/i');
		$doc = array("r" => $regex);
		$encoded = MongoJson::strict($doc);
		$this->assertEquals('{"r":{"$regex":"^acme.*corp","$options":"i"}}', $encoded);
	}

	public function testMinKey()
	{
		$min = new MongoMinKey;
		$doc = array("r" => $min);
		$encoded = MongoJson::strict($doc);
		$this->assertEquals('{"r":{"$minKey":1}}', $encoded);
	}

	public function testMaxKey()
	{
		$max = new MongoMaxKey;
		$doc = array("r" => $max);
		$encoded = MongoJson::strict($doc);
		$this->assertEquals('{"r":{"$maxKey":1}}', $encoded);
	}

	public function testNestedFields()
	{
		$doc = array(
					"_id" => new MongoId("51bfbd031ede017c19000000"),
					"nested" => array(
						"str" => "teststring",
						"int" => -1,
						"date" => new MongoDate(1371520561),
						"nested2" => array(
							"float" => 0.1,
							"oid" => new MongoId("51bfbd031ede017c19000001"),
							"regex" => new MongoRegex('/^acme.*corp/i'),
							"bin" => new MongoBinData(pack("nvc*", 0x1234, 0x5678, 65, 66)),
							"min" => new MongoMinKey,
							"max" => new MongoMaxKey
						)
					)
				);
		$encoded = MongoJson::strict($doc);
		$this->assertEquals('{"_id":{"$oid":"51bfbd031ede017c19000000"},"nested":{"str":"teststring","int":-1,"date":{"$date":1371520561000},"nested2":{"float":0.1,"oid":{"$oid":"51bfbd031ede017c19000001"},"regex":{"$regex":"^acme.*corp","$options":"i"},"bin":{"$binary":"\u00124xVAB","$type":"2"},"min":{"$minKey":1},"max":{"$maxKey":1}}}}', $encoded);
	}

}
