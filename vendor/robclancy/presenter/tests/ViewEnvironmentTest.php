<?php

use Mockery as m;
use Robbo\Presenter\Presenter;
use Robbo\Presenter\View\View;
use Illuminate\Support\Collection;
use Robbo\Presenter\View\Environment;
use Robbo\Presenter\PresentableInterface;

class ViewEnvironmentTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}

	public function testPresentableToPresenter()
	{
		$presenter = $this->getEnvironment()->makePresentable(new PresentableStub);

		$this->assertTrue($presenter instanceof Presenter);
	}

	public function testPresentablesToPresenters()
	{
		$from = array(
			'string' => 'string',
			'array' => array('test' => 'test'),
			'presentable' => new PresentableStub,
			'recurseMe' => array(array('presentable' => new PresentableStub)),
			'collection' => new Collection(array(
				'presentable' => new PresentableStub
			))
		);

		$to = $this->getEnvironment()->makePresentable($from);

		$this->assertSame($from['string'], $to['string']);
		$this->assertSame($from['array'], $to['array']);
		$this->assertTrue($to['presentable'] instanceof Presenter);
		$this->assertTrue($to['recurseMe'][0]['presentable'] instanceof Presenter);
		$this->assertTrue($to['collection']['presentable'] instanceof Presenter);
	}

	public function testMakeView()
	{
		$data = array(
			'meh' => 'zomg',
			'presentable' => new PresentableStub,
			'collection' => new Collection(array(
				'presentable' => new PresentableStub
			))
		);

		$env = $this->getEnvironment();
		$env->finder->shouldReceive('find')->once()->andReturn('test');

		$view = $env->make('test', $data);

		$this->assertTrue($view instanceof View);
		$this->assertSame($view['meh'], $data['meh']);
		$this->assertTrue($view['presentable'] instanceof Presenter);
		$this->assertTrue($view['collection']['presentable'] instanceof Presenter);
	}

	protected function getEnvironment()
	{
		return new EnvironmentStub(
			m::mock('Illuminate\View\Engines\EngineResolver'),
			m::mock('Illuminate\View\ViewFinderInterface'),
			m::mock('Illuminate\Events\Dispatcher')
		);
	}
}

class EnvironmentStub extends Environment {

	public $finder;

	protected function getEngineFromPath($path)
	{
		return m::mock('Illuminate\View\Engines\EngineInterface');
	}
}

class PresentableStub implements PresentableInterface {

	public function getPresenter()
	{
		return new EnvPresenterStub($this);
	}
}

class EnvPresenterStub extends Presenter {}