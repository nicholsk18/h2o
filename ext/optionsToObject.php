<?php

class OptionsToObject_Tag extends H2o_Node
{
    private $shortcut;
    private $nodelist;
    private $syntax = '/^(service.options)\s+as\s+(?P<short>[\w]+(:?\.[\w\d]+)?)$/';

    function __construct($argstring, $parser, $pos = 0)
    {
        if (!preg_match($this->syntax, $argstring, $matches)) {
            throw new TemplateSyntaxError('Invalid OptionsToObject tag syntax');
        }

        # extract shortcut
        $this->shortcut = $matches['short'];
        $this->nodelist = $parser->parse('endOptionsToObject');
    }

    function render($context, $stream)
    {
        $options = $context->getVariable('service.options');

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
