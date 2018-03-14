<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Caller;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Exception\ForbiddenOutputException;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\URLData;

Class CallerTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestController = file_get_contents('Original/Tests/HTTP/TestClasses/TestController.php');
        file_put_contents('Design/Control/Controllers/StratumTestUsersController.php', $TestController);
    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Control/Controllers/StratumTestUsersController.php');
    }

    public function test_throw_exception_if_output_is_sent_inside_the_calling_method()
    {
        $this->expectException(ForbiddenOutputException::class);


        (object) $controller = $this->getMockBuilder('Stratum\Custom\Controller\StratumTestUsersController')
                                    ->disableOriginalConstructor()
                                    ->setMethods(['create'])
                                    ->getMock();

        (object) $request = $this->createMock(Request::class);
        (object) $View = $this->createMock(HTML::class);
        (object) $Text = $this->createMock(Text::class);
        (object) $Json = $this->createMock(Json::class);
        (object) $Redirection = $this->createMock(Redirection::class);
        (object) $Dump = $this->createMock(Dump::class);
        (object) $Dispatcher = $this->createMock(Dispatcher::class);
        (object) $URLData = new URLData(['id' => 5534]);


        (object) $caller = new Caller;

        $caller->setObject($controller);
        $caller->setMethodName('output');

        $caller->setRequest($request);
        $caller->setView($View);
        $caller->setText($Text);
        $caller->setJson($Json);
        $caller->setRedirection($Redirection);
        $caller->setDump($Dump);
        $caller->setDispatcher($Dispatcher);
        $caller->setURLData($URLData);

        $caller->callMethod();

    }

    public function test_controller_method_is_called_with_the_requested_arguments()
    {
        (object) $controller = $this->getMockBuilder('Stratum\Custom\Controller\StratumTestUsersController')
                                    ->disableOriginalConstructor()
                                    ->setMethods(['create'])
                                    ->getMock();

        (object) $request = $this->createMock(Request::class);
        (object) $View = $this->createMock(HTML::class);
        (object) $Text = $this->createMock(Text::class);
        (object) $Json = $this->createMock(Json::class);
        (object) $Redirection = $this->createMock(Redirection::class);
        (object) $Dump = $this->createMock(Dump::class);
        (object) $Dispatcher = $this->createMock(Dispatcher::class);
        (object) $URLData = new URLData(['id' => 5534]);

        $View->expects($this->once())
            ->method('from')
            ->will($this->returnSelf());

        (object) $caller = new Caller;
        
        $caller->setObject($controller);
        $caller->setMethodName('list');

        $caller->setRequest($request);
        $caller->setView($View);
        $caller->setText($Text);
        $caller->setJson($Json);
        $caller->setRedirection($Redirection);
        $caller->setDump($Dump);
        $caller->setDispatcher($Dispatcher);
        $caller->setURLData($URLData);

        (object) $response = $caller->callMethod();

        $this->assertSame($View, $response);


    }

    public function test_controller_method_is_called_with_no_arguments()
    {
        (object) $controller = $this->getMockBuilder('Stratum\Custom\Controller\StratumTestUsersController')
                                    ->disableOriginalConstructor()
                                    ->setMethods(['create'])
                                    ->getMock();

        (object) $request = $this->createMock(Request::class);
        (object) $View = $this->createMock(HTML::class);
        (object) $Text = $this->createMock(Text::class);
        (object) $Json = $this->createMock(Json::class);
        (object) $Redirection = $this->createMock(Redirection::class);
        (object) $Dump = $this->createMock(Dump::class);
        (object) $Dispatcher = $this->createMock(Dispatcher::class);
        (object) $URLData = new URLData(['id' => 5534]);

        (object) $caller = new Caller;

        $caller->setObject($controller);
        $caller->setMethodName('noArguments');

        $caller->setRequest($request);
        $caller->setView($View);
        $caller->setText($Text);
        $caller->setJson($Json);
        $caller->setRedirection($Redirection);
        $caller->setDump($Dump);
        $caller->setDispatcher($Dispatcher);
        $caller->setURLData($URLData);

        $caller->callMethod();

    }

    public function test_controller_method_is_called_with_wildcard_names_as_arguments_only()
    {
        (object) $controller = $this->getMockBuilder('Stratum\Custom\Controller\StratumTestUsersController')
                                    ->disableOriginalConstructor()
                                    ->setMethods(['create'])
                                    ->getMock();

        (object) $request = $this->createMock(Request::class);
        (object) $View = $this->createMock(HTML::class);
        (object) $Text = $this->createMock(Text::class);
        (object) $Json = $this->createMock(Json::class);
        (object) $Redirection = $this->createMock(Redirection::class);
        (object) $Dump = $this->createMock(Dump::class);
        (object) $Dispatcher = $this->createMock(Dispatcher::class);
        (object) $URLData = new URLData([
            'id' => 5534,
            'commentId' => 7465
        ]);

        (object) $caller = new Caller;

        $caller->setObject($controller);
        $caller->setMethodName('onlyWildcards');

        $caller->setRequest($request);
        $caller->setView($View);
        $caller->setText($Text);
        $caller->setJson($Json);
        $caller->setRedirection($Redirection);
        $caller->setDump($Dump);
        $caller->setDispatcher($Dispatcher);
        $caller->setURLData($URLData);

        $caller->callMethod();

    }

    public function test_controller_method_is_called_with_type_hinted_only_arguments()
    {
        (object) $controller = $this->getMockBuilder('Stratum\Custom\Controller\StratumTestUsersController')
                                    ->disableOriginalConstructor()
                                    ->setMethods(['create'])
                                    ->getMock();

        (object) $request = $this->createMock(Request::class);
        (object) $View = $this->createMock(HTML::class);
        (object) $Text = $this->createMock(Text::class);
        (object) $Json = $this->createMock(Json::class);
        (object) $Redirection = $this->createMock(Redirection::class);
        (object) $Dump = $this->createMock(Dump::class);
        (object) $Dispatcher = $this->createMock(Dispatcher::class);
        (object) $URLData = new URLData([
            'id' => 5534,
            'commentId' => 7465
        ]);

        (object) $caller = new Caller;


        $request->expects($this->once())
                ->method('__get');

        $View->expects($this->once())
                ->method('from');

        $Text->expects($this->once())
                ->method('containing');

        $Json->expects($this->once())
                ->method('fromArray');

        $Redirection->expects($this->once())
                ->method('to');

        $Dump->expects($this->once())
                ->method('variable');

        $Dispatcher->expects($this->once())
                ->method('controller');



        $caller->setObject($controller);
        $caller->setMethodName('onlyTypeHinted');

        $caller->setRequest($request);
        $caller->setView($View);
        $caller->setText($Text);
        $caller->setJson($Json);
        $caller->setRedirection($Redirection);
        $caller->setDump($Dump);
        $caller->setDispatcher($Dispatcher);
        $caller->setURLData($URLData);

        $caller->callMethod();

    }

    public function test_controller_method_is_called_with_both_type_hinted_and_wilcard_arguments()
    {
        (object) $controller = $this->getMockBuilder('Stratum\Custom\Controller\StratumTestUsersController')
                                    ->disableOriginalConstructor()
                                    ->setMethods(['create'])
                                    ->getMock();

        (object) $request = $this->createMock(Request::class);
        (object) $View = $this->createMock(HTML::class);
        (object) $Text = $this->createMock(Text::class);
        (object) $Json = $this->createMock(Json::class);
        (object) $Redirection = $this->createMock(Redirection::class);
        (object) $Dump = $this->createMock(Dump::class);
        (object) $Dispatcher = $this->createMock(Dispatcher::class);
        (object) $URLData = new URLData([
            'id' => 5534,
            'commentId' => 7465
        ]);

        (object) $caller = new Caller;


        $request->expects($this->once())
                ->method('__get');

        $View->expects($this->once())
                ->method('from');

        $Text->expects($this->once())
                ->method('containing');

        $Json->expects($this->once())
                ->method('fromArray');

        $Redirection->expects($this->once())
                ->method('to');

        $Dump->expects($this->once())
                ->method('variable');

        $Dispatcher->expects($this->once())
                ->method('controller');

        

        $caller->setObject($controller);
        $caller->setMethodName('typeHintedAndWildcards');

        $caller->setRequest($request);
        $caller->setView($View);
        $caller->setText($Text);
        $caller->setJson($Json);
        $caller->setRedirection($Redirection);
        $caller->setDump($Dump);
        $caller->setDispatcher($Dispatcher);
        $caller->setURLData($URLData);

        $caller->callMethod();

    }

    public function test_throws_exception_if_requested_object_is_not_supported()
    {

        $this->expectException(\InvalidArgumentException::class);

        (object) $controller = $this->getMockBuilder('Stratum\Custom\Controller\StratumTestUsersController')
                                    ->disableOriginalConstructor()
                                    ->setMethods(['create'])
                                    ->getMock();

        (object) $request = $this->createMock(Request::class);
        (object) $View = $this->createMock(HTML::class);
        (object) $Text = $this->createMock(Text::class);
        (object) $Json = $this->createMock(Json::class);
        (object) $Redirection = $this->createMock(Redirection::class);
        (object) $Dump = $this->createMock(Dump::class);
        (object) $Dispatcher = $this->createMock(Dispatcher::class);
        (object) $URLData = new URLData([
            'id' => 5534,
            'commentId' => 7465
        ]);

        (object) $caller = new Caller;

        $caller->setObject($controller);
        $caller->setMethodName('unknownObject');

        $caller->setRequest($request);
        $caller->setView($View);
        $caller->setText($Text);
        $caller->setJson($Json);
        $caller->setRedirection($Redirection);
        $caller->setDump($Dump);
        $caller->setDispatcher($Dispatcher);
        $caller->setURLData($URLData);

        $caller->callMethod();

    }

    public function test_throws_exception_if_an_unknown_wildcard_name_is_requested()
    {

        $this->expectException(\InvalidArgumentException::class);
        
        (object) $controller = $this->getMockBuilder('Stratum\Custom\Controller\StratumTestUsersController')
                                    ->disableOriginalConstructor()
                                    ->setMethods(['create'])
                                    ->getMock();

        (object) $request = $this->createMock(Request::class);
        (object) $View = $this->createMock(HTML::class);
        (object) $Text = $this->createMock(Text::class);
        (object) $Json = $this->createMock(Json::class);
        (object) $Redirection = $this->createMock(Redirection::class);
        (object) $Dump = $this->createMock(Dump::class);
        (object) $Dispatcher = $this->createMock(Dispatcher::class);
        (object) $URLData = new URLData([
            'id' => 5534,
            'commentId' => 7465
        ]);

        (object) $caller = new Caller;

        $caller->setObject($controller);
        $caller->setMethodName('unknownWildcard');

        $caller->setRequest($request);
        $caller->setView($View);
        $caller->setText($Text);
        $caller->setJson($Json);
        $caller->setRedirection($Redirection);
        $caller->setDump($Dump);
        $caller->setDispatcher($Dispatcher);
        $caller->setURLData($URLData);

        $caller->callMethod();

    }











}