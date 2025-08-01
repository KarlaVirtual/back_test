<?php
/*
 * This file is part of the PHPUnit_MockObject package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\Instantiator\Instantiator;
use Doctrine\Instantiator\Exception\InvalidArgumentException as InstantiatorInvalidArgumentException;
use Doctrine\Instantiator\Exception\UnexpectedValueException as InstantiatorUnexpectedValueException;

if (!function_exists('trait_exists')) {
    function trait_exists($traitname, $autoload = true)
    {
        return false;
    }
}

/**
 * Mock Object Code Generator
 *
 * @since Class available since Release 1.0.0
 */
class PHPUnit_Framework_MockObject_Generator
{
    /**
     * @var array
     */
    private static $cache = array();

    /**
     * @var array
     */
    protected $blacklistedMethodNames = array(
      '__CLASS__'       => true,
      '__DIR__'         => true,
      '__FILE__'        => true,
      '__FUNCTION__'    => true,
      '__LINE__'        => true,
      '__METHOD__'      => true,
      '__NAMESPACE__'   => true,
      '__TRAIT__'       => true,
      '__clone'         => true,
      '__halt_compiler' => true,
      'abstract'        => true,
      'and'             => true,
      'array'           => true,
      'as'              => true,
      'break'           => true,
      'callable'        => true,
      'case'            => true,
      'catch'           => true,
      'class'           => true,
      'clone'           => true,
      'const'           => true,
      'continue'        => true,
      'declare'         => true,
      'default'         => true,
      'die'             => true,
      'do'              => true,
      'echo'            => true,
      'else'            => true,
      'elseif'          => true,
      'empty'           => true,
      'enddeclare'      => true,
      'endfor'          => true,
      'endforeach'      => true,
      'endif'           => true,
      'endswitch'       => true,
      'endwhile'        => true,
      'eval'            => true,
      'exit'            => true,
      'expects'         => true,
      'extends'         => true,
      'final'           => true,
      'for'             => true,
      'foreach'         => true,
      'function'        => true,
      'global'          => true,
      'goto'            => true,
      'if'              => true,
      'implements'      => true,
      'include'         => true,
      'include_once'    => true,
      'instanceof'      => true,
      'insteadof'       => true,
      'interface'       => true,
      'isset'           => true,
      'list'            => true,
      'namespace'       => true,
      'new'             => true,
      'or'              => true,
      'print'           => true,
      'private'         => true,
      'protected'       => true,
      'public'          => true,
      'require'         => true,
      'require_once'    => true,
      'return'          => true,
      'static'          => true,
      'switch'          => true,
      'throw'           => true,
      'trait'           => true,
      'try'             => true,
      'unset'           => true,
      'use'             => true,
      'var'             => true,
      'while'           => true,
      'xor'             => true
    );

    /**
     * Returns a mock object for the specified class.
     *
     * @param  array|string                                  $type
     * @param  array                                         $methods
     * @param  array                                         $arguments
     * @param  string                                        $mockClassName
     * @param  bool                                          $callOriginalConstructor
     * @param  bool                                          $callOriginalClone
     * @param  bool                                          $callAutoload
     * @param  bool                                          $cloneArguments
     * @param  bool                                          $callOriginalMethods
     * @param  object                                        $proxyTarget
     * @return object
     * @throws InvalidArgumentException
     * @throws PHPUnit_Framework_Exception
     * @throws PHPUnit_Framework_MockObject_RuntimeException
     * @since  Method available since Release 1.0.0
     */
    public function getMock($type, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $cloneArguments = true, $callOriginalMethods = false, $proxyTarget = null)
    {
        if (!is_array($type) && !is_string($type)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'array or string');
        }

        if (!is_string($mockClassName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(4, 'string');
        }

        if (!is_array($methods) && !is_null($methods)) {
            throw new InvalidArgumentException;
        }

        if ($type === 'Traversable' || $type === '\\Traversable') {
            $type = 'Iterator';
        }

        if (is_array($type)) {
            $type = array_unique(array_map(
                function ($type) {
                    if ($type === 'Traversable' ||
                      $type === '\\Traversable' ||
                      $type === '\\Iterator') {
                        return 'Iterator';
                    }

                    return $type;
                },
                $type
            ));
        }

        if (null !== $methods) {
            foreach ($methods as $method) {
                if (!preg_match('~[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*~', $method)) {
                    throw new PHPUnit_Framework_Exception(
                        sprintf(
                            'Cannot stub or mock method with invalid name "%s"',
                            $method
                        )
                    );
                }
            }

            if ($methods != array_unique($methods)) {
                throw new PHPUnit_Framework_MockObject_RuntimeException(
                    sprintf(
                        'Cannot stub or mock using a method list that contains duplicates: "%s"',
                        implode(', ', $methods)
                    )
                );
            }
        }

        if ($mockClassName != '' && class_exists($mockClassName, false)) {
            $reflect = new ReflectionClass($mockClassName);

            if (!$reflect->implementsInterface('PHPUnit_Framework_MockObject_MockObject')) {
                throw new PHPUnit_Framework_MockObject_RuntimeException(
                    sprintf(
                        'Class "%s" already exists.',
                        $mockClassName
                    )
                );
            }
        }

        $mock = $this->generate(
            $type,
            $methods,
            $mockClassName,
            $callOriginalClone,
            $callAutoload,
            $cloneArguments,
            $callOriginalMethods
        );

        return $this->getObject(
            $mock['code'],
            $mock['mockClassName'],
            $type,
            $callOriginalConstructor,
            $callAutoload,
            $arguments,
            $callOriginalMethods,
            $proxyTarget
        );
    }

    /**
     * @param  string       $code
     * @param  string       $className
     * @param  array|string $type
     * @param  bool         $callOriginalConstructor
     * @param  bool         $callAutoload
     * @param  array        $arguments
     * @param  bool         $callOriginalMethods
     * @param  object       $proxyTarget
     * @return object
     */
    protected function getObject($code, $className, $type = '', $callOriginalConstructor = false, $callAutoload = false, array $arguments = array(), $callOriginalMethods = false, $proxyTarget = null)
    {
        $this->evalClass($code, $className);

        if ($callOriginalConstructor &&
            is_string($type) &&
            !interface_exists($type, $callAutoload)) {
            if (oldCount($arguments) == 0) {
                $object = new $className;
            } else {
                $class  = new ReflectionClass($className);
                $object = $class->newInstanceArgs($arguments);
            }
        } else {
            try {
                $instantiator = new Instantiator;
                $object       = $instantiator->instantiate($className);
            } catch (InstantiatorUnexpectedValueException $exception) {
                if ($exception->getPrevious()) {
                    $exception = $exception->getPrevious();
                }

                throw new PHPUnit_Framework_MockObject_RuntimeException(
                    $exception->getMessage()
                );
            } catch (InstantiatorInvalidArgumentException $exception) {
                throw new PHPUnit_Framework_MockObject_RuntimeException(
                    $exception->getMessage()
                );
            }
        }

        if ($callOriginalMethods) {
            if (!is_object($proxyTarget)) {
                if (oldCount($arguments) == 0) {
                    $proxyTarget = new $type;
                } else {
                    $class       = new ReflectionClass($type);
                    $proxyTarget = $class->newInstanceArgs($arguments);
                }
            }

            $object->__phpunit_setOriginalObject($proxyTarget);
        }

        return $object;
    }

    /**
     * @param string $code
     * @param string $className
     */
    protected function evalClass($code, $className)
    {
        if (!class_exists($className, false)) {
            eval($code);
        }
    }

    /**
     * Returns a mock object for the specified abstract class with all abstract
     * methods of the class mocked. Concrete methods to mock can be specified with
     * the last parameter
     *
     * @param  string $originalClassName
     * @param  array  $arguments
     * @param  string $mockClassName
     * @param  bool   $callOriginalConstructor
     * @param  bool   $callOriginalClone
     * @param  bool   $callAutoload
     * @param  array  $mockedMethods
     * @param  bool   $cloneArguments
     * @return object
     * @since  Method available since Release 1.0.0
     * @throws PHPUnit_Framework_MockObject_RuntimeException
     * @throws PHPUnit_Framework_Exception
     */
    public function getMockForAbstractClass($originalClassName, array $arguments = array(), $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $mockedMethods = array(), $cloneArguments = true)
    {
        if (!is_string($originalClassName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_string($mockClassName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(3, 'string');
        }

        if (class_exists($originalClassName, $callAutoload) ||
            interface_exists($originalClassName, $callAutoload)) {
            $reflector = new ReflectionClass($originalClassName);
            $methods   = $mockedMethods;

            foreach ($reflector->getMethods() as $method) {
                if ($method->isAbstract() && !in_array($method->getName(), $methods)) {
                    $methods[] = $method->getName();
                }
            }

            if (empty($methods)) {
                $methods = null;
            }

            return $this->getMock(
                $originalClassName,
                $methods,
                $arguments,
                $mockClassName,
                $callOriginalConstructor,
                $callOriginalClone,
                $callAutoload,
                $cloneArguments
            );
        } else {
            throw new PHPUnit_Framework_MockObject_RuntimeException(
                sprintf('Class "%s" does not exist.', $originalClassName)
            );
        }
    }

    /**
     * Returns a mock object for the specified trait with all abstract methods
     * of the trait mocked. Concrete methods to mock can be specified with the
     * `$mockedMethods` parameter.
     *
     * @param  string $traitName
     * @param  array  $arguments
     * @param  string $mockClassName
     * @param  bool   $callOriginalConstructor
     * @param  bool   $callOriginalClone
     * @param  bool   $callAutoload
     * @param  array  $mockedMethods
     * @param  bool   $cloneArguments
     * @return object
     * @since  Method available since Release 1.2.3
     * @throws PHPUnit_Framework_MockObject_RuntimeException
     * @throws PHPUnit_Framework_Exception
     */
    public function getMockForTrait($traitName, array $arguments = array(), $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $mockedMethods = array(), $cloneArguments = true)
    {
        if (!is_string($traitName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_string($mockClassName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(3, 'string');
        }

        if (!trait_exists($traitName, $callAutoload)) {
            throw new PHPUnit_Framework_MockObject_RuntimeException(
                sprintf(
                    'Trait "%s" does not exist.',
                    $traitName
                )
            );
        }

        $className = $this->generateClassName(
            $traitName,
            '',
            'Trait_'
        );

        $templateDir   = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Generator' .
                         DIRECTORY_SEPARATOR;
        $classTemplate = new Text_Template(
            $templateDir . 'trait_class.tpl'
        );

        $classTemplate->setVar(
            array(
            'prologue'   => 'abstract ',
            'class_name' => $className['className'],
            'trait_name' => $traitName
            )
        );

        $this->evalClass(
            $classTemplate->render(),
            $className['className']
        );

        return $this->getMockForAbstractClass($className['className'], $arguments, $mockClassName, $callOriginalConstructor, $callOriginalClone, $callAutoload, $mockedMethods, $cloneArguments);
    }

    /**
     * Returns an object for the specified trait.
     *
     * @param  string $traitName
     * @param  array  $arguments
     * @param  string $traitClassName
     * @param  bool   $callOriginalConstructor
     * @param  bool   $callOriginalClone
     * @param  bool   $callAutoload
     * @return object
     * @since  Method available since Release 1.1.0
     * @throws PHPUnit_Framework_MockObject_RuntimeException
     * @throws PHPUnit_Framework_Exception
     */
    public function getObjectForTrait($traitName, array $arguments = array(), $traitClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true)
    {
        if (!is_string($traitName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_string($traitClassName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(3, 'string');
        }

        if (!trait_exists($traitName, $callAutoload)) {
            throw new PHPUnit_Framework_MockObject_RuntimeException(
                sprintf(
                    'Trait "%s" does not exist.',
                    $traitName
                )
            );
        }

        $className = $this->generateClassName(
            $traitName,
            $traitClassName,
            'Trait_'
        );

        $templateDir   = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Generator' .
                         DIRECTORY_SEPARATOR;
        $classTemplate = new Text_Template(
            $templateDir . 'trait_class.tpl'
        );

        $classTemplate->setVar(
            array(
            'prologue'   => '',
            'class_name' => $className['className'],
            'trait_name' => $traitName
            )
        );

        return $this->getObject(
            $classTemplate->render(),
            $className['className']
        );
    }

    /**
     * @param  array|string $type
     * @param  array        $methods
     * @param  string       $mockClassName
     * @param  bool         $callOriginalClone
     * @param  bool         $callAutoload
     * @param  bool         $cloneArguments
     * @param  bool         $callOriginalMethods
     * @return array
     */
    public function generate($type, array $methods = null, $mockClassName = '', $callOriginalClone = true, $callAutoload = true, $cloneArguments = true, $callOriginalMethods = false)
    {
        if (is_array($type)) {
            sort($type);
        }

        if ($mockClassName == '') {
            $key = md5(
                is_array($type) ? implode('_', $type) : $type .
                serialize($methods) .
                serialize($callOriginalClone) .
                serialize($cloneArguments) .
                serialize($callOriginalMethods)
            );

            if (isset(self::$cache[$key])) {
                return self::$cache[$key];
            }
        }

        $mock = $this->generateMock(
            $type,
            $methods,
            $mockClassName,
            $callOriginalClone,
            $callAutoload,
            $cloneArguments,
            $callOriginalMethods
        );

        if (isset($key)) {
            self::$cache[$key] = $mock;
        }

        return $mock;
    }

    /**
     * @param  string                                        $wsdlFile
     * @param  string                                        $className
     * @param  array                                         $methods
     * @param  array                                         $options
     * @return string
     * @throws PHPUnit_Framework_MockObject_RuntimeException
     */
    public function generateClassFromWsdl($wsdlFile, $className, array $methods = array(), array $options = array())
    {
        if (!extension_loaded('soap')) {
            throw new PHPUnit_Framework_MockObject_RuntimeException(
                'The SOAP extension is required to generate a mock object from WSDL.'
            );
        }

        $options  = array_merge($options, array('cache_wsdl' => WSDL_CACHE_NONE));
        $client   = new SoapClient($wsdlFile, $options);
        $_methods = array_unique($client->__getFunctions());
        unset($client);

        sort($_methods);

        $templateDir    = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Generator' . DIRECTORY_SEPARATOR;
        $methodTemplate = new Text_Template($templateDir . 'wsdl_method.tpl');
        $methodsBuffer  = '';

        foreach ($_methods as $method) {
            $nameStart = strpos($method, ' ') + 1;
            $nameEnd   = strpos($method, '(');
            $name      = substr($method, $nameStart, $nameEnd - $nameStart);

            if (empty($methods) || in_array($name, $methods)) {
                $args    = explode(
                    ',',
                    substr(
                        $method,
                        $nameEnd + 1,
                        strpos($method, ')') - $nameEnd - 1
                    )
                );
                $numArgs = oldCount($args);

                for ($i = 0; $i < $numArgs; $i++) {
                    $args[$i] = substr($args[$i], strpos($args[$i], '$'));
                }

                $methodTemplate->setVar(
                    array(
                        'method_name' => $name,
                        'arguments'   => implode(', ', $args)
                    )
                );

                $methodsBuffer .= $methodTemplate->render();
            }
        }

        $optionsBuffer = 'array(';

        foreach ($options as $key => $value) {
            $optionsBuffer .= $key . ' => ' . $value;
        }

        $optionsBuffer .= ')';

        $classTemplate = new Text_Template($templateDir . 'wsdl_class.tpl');
        $namespace     = '';

        if (strpos($className, '\\') !== false) {
            $parts     = explode('\\', $className);
            $className = array_pop($parts);
            $namespace = 'namespace ' . implode('\\', $parts) . ';' . "\n\n";
        }

        $classTemplate->setVar(
            array(
                'namespace'  => $namespace,
                'class_name' => $className,
                'wsdl'       => $wsdlFile,
                'options'    => $optionsBuffer,
                'methods'    => $methodsBuffer
            )
        );

        return $classTemplate->render();
    }

    /**
     * @param  array|string                $type
     * @param  array|null                  $methods
     * @param  string                      $mockClassName
     * @param  bool                        $callOriginalClone
     * @param  bool                        $callAutoload
     * @param  bool                        $cloneArguments
     * @param  bool                        $callOriginalMethods
     * @return array
     * @throws PHPUnit_Framework_Exception
     */
    protected function generateMock($type, $methods, $mockClassName, $callOriginalClone, $callAutoload, $cloneArguments, $callOriginalMethods)
    {
        $templateDir   = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Generator' .
                         DIRECTORY_SEPARATOR;
        $classTemplate = new Text_Template(
            $templateDir . 'mocked_class.tpl'
        );

        $additionalInterfaces = array();
        $cloneTemplate        = '';
        $isClass              = false;
        $isInterface          = false;

        $mockClassName = $this->generateClassName(
            $type,
            $mockClassName,
            'Mock_'
        );

        if (is_array($type)) {
            foreach ($type as $_type) {
                if (!interface_exists($_type, $callAutoload)) {
                    throw new PHPUnit_Framework_Exception(
                        sprintf(
                            'Interface "%s" does not exist.',
                            $_type
                        )
                    );
                }

                $additionalInterfaces[] = $_type;

                foreach ($this->getClassMethods($_type) as $method) {
                    if (in_array($method, $methods)) {
                        throw new PHPUnit_Framework_Exception(
                            sprintf(
                                'Duplicate method "%s" not allowed.',
                                $method
                            )
                        );
                    }

                    $methods[] = $method;
                }
            }
        }

        if (class_exists($mockClassName['fullClassName'], $callAutoload)) {
            $isClass = true;
        } else {
            if (interface_exists($mockClassName['fullClassName'], $callAutoload)) {
                $isInterface = true;
            }
        }

        if (!class_exists($mockClassName['fullClassName'], $callAutoload) &&
            !interface_exists($mockClassName['fullClassName'], $callAutoload)) {
            $prologue = 'class ' . $mockClassName['originalClassName'] . "\n{\n}\n\n";

            if (!empty($mockClassName['namespaceName'])) {
                $prologue = 'namespace ' . $mockClassName['namespaceName'] .
                            " {\n\n" . $prologue . "}\n\n" .
                            "namespace {\n\n";

                $epilogue = "\n\n}";
            }

            $cloneTemplate = new Text_Template(
                $templateDir . 'mocked_clone.tpl'
            );
        } else {
            $class = new ReflectionClass($mockClassName['fullClassName']);

            if ($class->isFinal()) {
                throw new PHPUnit_Framework_Exception(
                    sprintf(
                        'Class "%s" is declared "final" and cannot be mocked.',
                        $mockClassName['fullClassName']
                    )
                );
            }

            if ($class->hasMethod('__clone')) {
                $cloneMethod = $class->getMethod('__clone');

                if (!$cloneMethod->isFinal()) {
                    if ($callOriginalClone && !$isInterface) {
                        $cloneTemplate = new Text_Template(
                            $templateDir . 'unmocked_clone.tpl'
                        );
                    } else {
                        $cloneTemplate = new Text_Template(
                            $templateDir . 'mocked_clone.tpl'
                        );
                    }
                }
            } else {
                $cloneTemplate = new Text_Template(
                    $templateDir . 'mocked_clone.tpl'
                );
            }
        }

        if (is_object($cloneTemplate)) {
            $cloneTemplate = $cloneTemplate->render();
        }

        if (is_array($methods) && empty($methods) &&
            ($isClass || $isInterface)) {
            $methods = $this->getClassMethods($mockClassName['fullClassName']);
        }

        if (!is_array($methods)) {
            $methods = array();
        }

        $mockedMethods = '';

        if (isset($class)) {
            // https://github.com/sebastianbergmann/phpunit-mock-objects/issues/103
            if ($isInterface && $class->implementsInterface('Traversable') &&
                !$class->implementsInterface('Iterator') &&
                !$class->implementsInterface('IteratorAggregate')) {
                $additionalInterfaces[] = 'Iterator';
                $methods                = array_merge($methods, $this->getClassMethods('Iterator'));
            }

            foreach ($methods as $methodName) {
                try {
                    $method = $class->getMethod($methodName);

                    if ($this->canMockMethod($method)) {
                        $mockedMethods .= $this->generateMockedMethodDefinitionFromExisting(
                            $templateDir,
                            $method,
                            $cloneArguments,
                            $callOriginalMethods
                        );
                    }
                } catch (ReflectionException $e) {
                    $mockedMethods .= $this->generateMockedMethodDefinition(
                        $templateDir,
                        $mockClassName['fullClassName'],
                        $methodName,
                        $cloneArguments
                    );
                }
            }
        } else {
            foreach ($methods as $methodName) {
                $mockedMethods .= $this->generateMockedMethodDefinition(
                    $templateDir,
                    $mockClassName['fullClassName'],
                    $methodName,
                    $cloneArguments
                );
            }
        }

        $method = '';

        if (!in_array('method', $methods)) {
            $methodTemplate = new Text_Template(
                $templateDir . 'mocked_class_method.tpl'
            );

            $method = $methodTemplate->render();
        }

        $classTemplate->setVar(
            array(
            'prologue'          => isset($prologue) ? $prologue : '',
            'epilogue'          => isset($epilogue) ? $epilogue : '',
            'class_declaration' => $this->generateMockClassDeclaration(
                $mockClassName,
                $isInterface,
                $additionalInterfaces
            ),
            'clone'             => $cloneTemplate,
            'mock_class_name'   => $mockClassName['className'],
            'mocked_methods'    => $mockedMethods,
            'method'            => $method
            )
        );

        return array(
          'code'          => $classTemplate->render(),
          'mockClassName' => $mockClassName['className']
        );
    }

    /**
     * @param  array|string $type
     * @param  string       $className
     * @param  string       $prefix
     * @return array
     */
    protected function generateClassName($type, $className, $prefix)
    {
        if (is_array($type)) {
            $type = implode('_', $type);
        }

        if ($type[0] == '\\') {
            $type = substr($type, 1);
        }

        $classNameParts = explode('\\', $type);

        if (oldCount($classNameParts) > 1) {
            $type          = array_pop($classNameParts);
            $namespaceName = implode('\\', $classNameParts);
            $fullClassName = $namespaceName . '\\' . $type;
        } else {
            $namespaceName = '';
            $fullClassName = $type;
        }

        if ($className == '') {
            do {
                $className = $prefix . $type . '_' .
                             substr(md5(microtime()), 0, 8);
            } while (class_exists($className, false));
        }

        return array(
          'className'         => $className,
          'originalClassName' => $type,
          'fullClassName'     => $fullClassName,
          'namespaceName'     => $namespaceName
        );
    }

    /**
     * @param  array $mockClassName
     * @param  bool  $isInterface
     * @param  array $additionalInterfaces
     * @return array
     */
    protected function generateMockClassDeclaration(array $mockClassName, $isInterface, array $additionalInterfaces = array())
    {
        $buffer = 'class ';

        $additionalInterfaces[] = 'PHPUnit_Framework_MockObject_MockObject';
        $interfaces             = implode(', ', $additionalInterfaces);

        if ($isInterface) {
            $buffer .= sprintf(
                '%s implements %s',
                $mockClassName['className'],
                $interfaces
            );

            if (!in_array($mockClassName['originalClassName'], $additionalInterfaces)) {
                $buffer .= ', ';

                if (!empty($mockClassName['namespaceName'])) {
                    $buffer .= $mockClassName['namespaceName'] . '\\';
                }

                $buffer .= $mockClassName['originalClassName'];
            }
        } else {
            $buffer .= sprintf(
                '%s extends %s%s implements %s',
                $mockClassName['className'],
                !empty($mockClassName['namespaceName']) ? $mockClassName['namespaceName'] . '\\' : '',
                $mockClassName['originalClassName'],
                $interfaces
            );
        }

        return $buffer;
    }

    /**
     * @param  string           $templateDir
     * @param  ReflectionMethod $method
     * @param  bool             $cloneArguments
     * @param  bool             $callOriginalMethods
     * @return string
     */
    protected function generateMockedMethodDefinitionFromExisting($templateDir, ReflectionMethod $method, $cloneArguments, $callOriginalMethods)
    {
        if ($method->isPrivate()) {
            $modifier = 'private';
        } elseif ($method->isProtected()) {
            $modifier = 'protected';
        } else {
            $modifier = 'public';
        }

        if ($method->isStatic()) {
            $modifier .= ' static';
        }

        if ($method->returnsReference()) {
            $reference = '&';
        } else {
            $reference = '';
        }

        return $this->generateMockedMethodDefinition(
            $templateDir,
            $method->getDeclaringClass()->getName(),
            $method->getName(),
            $cloneArguments,
            $modifier,
            $this->getMethodParameters($method),
            $this->getMethodParameters($method, true),
            $reference,
            $callOriginalMethods,
            $method->isStatic()
        );
    }

    /**
     * @param  string $templateDir
     * @param  string $className
     * @param  string $methodName
     * @param  bool   $cloneArguments
     * @param  string $modifier
     * @param  string $arguments_decl
     * @param  string $arguments_call
     * @param  string $reference
     * @param  bool   $callOriginalMethods
     * @param  bool   $static
     * @return string
     */
    protected function generateMockedMethodDefinition($templateDir, $className, $methodName, $cloneArguments = true, $modifier = 'public', $arguments_decl = '', $arguments_call = '', $reference = '', $callOriginalMethods = false, $static = false)
    {
        if ($static) {
            $templateFile = 'mocked_static_method.tpl';
        } else {
            $templateFile = sprintf(
                '%s_method.tpl',
                $callOriginalMethods ? 'proxied' : 'mocked'
            );
        }

        $template = new Text_Template($templateDir . $templateFile);

        $template->setVar(
            array(
            'arguments_decl'  => $arguments_decl,
            'arguments_call'  => $arguments_call,
            'arguments_count' => !empty($arguments_call) ? count(explode(',', $arguments_call)) : 0,
            'class_name'      => $className,
            'method_name'     => $methodName,
            'modifier'        => $modifier,
            'reference'       => $reference,
            'clone_arguments' => $cloneArguments ? 'TRUE' : 'FALSE'
            )
        );

        return $template->render();
    }

    /**
     * @param  ReflectionMethod $method
     * @return bool
     */
    protected function canMockMethod(ReflectionMethod $method)
    {
        if ($method->isConstructor() ||
            $method->isFinal() ||
            $method->isPrivate() ||
            isset($this->blacklistedMethodNames[$method->getName()])) {
            return false;
        }

        return true;
    }

    /**
     * Returns the parameters of a function or method.
     *
     * @param  ReflectionMethod                              $method
     * @param  bool                                          $forCall
     * @return string
     * @throws PHPUnit_Framework_MockObject_RuntimeException
     * @since  Method available since Release 2.0.0
     */
    protected function getMethodParameters(ReflectionMethod $method, $forCall = false)
    {
        $parameters = array();

        foreach ($method->getParameters() as $i => $parameter) {
            $name = '$' . $parameter->getName();

            /* Note: PHP extensions may use empty names for reference arguments
             * or "..." for methods taking a variable number of arguments.
             */
            if ($name === '$' || $name === '$...') {
                $name = '$arg' . $i;
            }

            if ($this->isVariadic($parameter)) {
                if ($forCall) {
                    continue;
                } else {
                    $name = '...' . $name;
                }
            }

            $default         = '';
            $reference       = '';
            $typeDeclaration = '';

            if (!$forCall) {
                if ($this->hasType($parameter)) {
                    $typeDeclaration = (string) $parameter->getType() . ' ';
                } elseif ($parameter->isArray()) {
                    $typeDeclaration = 'array ';
                } elseif ((defined('HHVM_VERSION') || version_compare(PHP_VERSION, '5.4.0', '>='))
                          && $parameter->isCallable()) {
                    $typeDeclaration = 'callable ';
                } else {
                    try {
                        $class = $parameter->getClass();
                    } catch (ReflectionException $e) {
                        throw new PHPUnit_Framework_MockObject_RuntimeException(
                            sprintf(
                                'Cannot mock %s::%s() because a class or ' .
                                'interface used in the signature is not loaded',
                                $method->getDeclaringClass()->getName(),
                                $method->getName()
                            ),
                            0,
                            $e
                        );
                    }

                    if ($class !== null) {
                        $typeDeclaration = $class->getName() . ' ';
                    }
                }

                if (!$this->isVariadic($parameter)) {
                    if ($parameter->isDefaultValueAvailable()) {
                        $value   = $parameter->getDefaultValue();
                        $default = ' = ' . var_export($value, true);
                    } elseif ($parameter->isOptional()) {
                        $default = ' = null';
                    }
                }
            }

            if ($parameter->isPassedByReference()) {
                $reference = '&';
            }

            $parameters[] = $typeDeclaration . $reference . $name . $default;
        }

        return implode(', ', $parameters);
    }

    /**
     * @param  ReflectionParameter $parameter
     * @return bool
     * @since  Method available since Release 2.2.1
     */
    private function isVariadic(ReflectionParameter $parameter)
    {
        return method_exists('ReflectionParameter', 'isVariadic') && $parameter->isVariadic();
    }

    /**
     * @param  ReflectionParameter $parameter
     * @return bool
     * @since  Method available since Release 2.3.4
     */
    private function hasType(ReflectionParameter $parameter)
    {
        return method_exists('ReflectionParameter', 'hasType') && $parameter->hasType();
    }

    /**
     * @param  string $className
     * @return array
     * @since  Method available since Release 2.3.2
     */
    private function getClassMethods($className)
    {
        $class   = new ReflectionClass($className);
        $methods = array();

        foreach ($class->getMethods() as $method) {
            if (($method->isPublic() || $method->isAbstract()) && !in_array($method->getName(), $methods)) {
                $methods[] = $method->getName();
            }
        }

        return $methods;
    }
}
