<?php

namespace hellaEngine\Supports\Common\Util;

use PhpParser\ParserAbstract;
use PhpParser\Lexer\Emulative;
use PhpParser\Parser;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Const_;

class CommonUtilReference {
	/**
	 * 获取常量说明文档
	 *
	 * @param unknown $classname
	 * @return multitype:|multitype:string
	 */
	static function getConstDocument($classname) {
		if (! class_exists ( $classname )) {
			return [];
		}

		$classref = new \ReflectionClass ( $classname );
		$filename = $classref->getFileName ();

		$filecontents = file_get_contents ( $filename );

		$parser = new Parser ( new Emulative () );
		$stmt = $parser->parse ( $filecontents );

		$constants_Document = [ ];

		$classnode = self::_get_class_node ( $stmt, $classref->getShortName () );
		if (is_null ( $classnode )) {
			return $constants_Document;
		}

		foreach ( $classnode->stmts as $value ) {
			if ($value instanceof ClassConst) {
				foreach ( $value->consts as $valueconsts ) {
					if ($valueconsts instanceof Const_) {
						$docComment = $value->getDocComment ();
						if (! is_null ( $docComment )) {
							$constants_Document [$valueconsts->name] = $value->getDocComment ()->getText ();
						}
					}
				}
			}
		}

		return $constants_Document;
	}
	/**
	 * 获取指定类的结构
	 *
	 * @param array $stmt
	 * @param unknown $classname
	 * @return Class_|NULL
	 */
	private static function _get_class_node(array $stmt, $classname) {
		foreach ( $stmt as $node_ ) {
			if ($node_ instanceof Namespace_) {
				$classnode_ = self::_get_class_node ( $node_->stmts, $classname );
				if (! is_null ( $classnode_ )) {
					return $classnode_;
				}
			} elseif ($node_ instanceof Class_) {

				if ($node_->name === $classname) {

					return $node_;
				}
			}
		}
		return null;
	}
}