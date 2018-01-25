<?php

namespace AppBundle\DoctrineFunctions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * DateAddFunction ::=
 *     "RAND" "(" ArithmeticPrimary ")"
 */
class Rand extends FunctionNode {
    public $randInt = '';

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->randInt = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);

    }

    public function getSql(SqlWalker $sqlWalker) {
        return 'RAND(' . $this->randInt->dispatch($sqlWalker) . ')';
    }
}