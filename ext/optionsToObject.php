<?php

class OptionsToObject_Tag extends H2o_Node
{
    public $position;
    private $variable;
    private $seperator;
    private $shortcut;
    private $nodelist;
    private $syntax = '/^(?P<var>[\w]+(:?\.[\w\d]+)*)\s+as\s+(?P<short>[\w]+(:?\.[\w\d]+)?)$/';

    function __construct($argstring, $parser, $pos = 0)
    {
        if (!preg_match($this->syntax, $argstring, $matches)) {
            throw new TemplateSyntaxError('Invalid OptionsToObject tag syntax');
        }

        # extract the long name, separator, and shortcut
        $this->variable = $matches['var'];
        $this->shortcut = $matches['short'];
        $this->nodelist = $parser->parse('endOptionsToObject');
    }

    function render($context, $stream)
    {
        $options = $context->getVariable($this->variable);

        $options_obj = array();
        foreach($options as $option) {
            $options_obj[$option->option_name] = $option->option_value;
        }
        $context->push([$this->shortcut => (object)$options_obj]);
        $this->nodelist->render($context, $stream);
        $context->pop();
    }
}

H2o::addTag('optionsToObject');
