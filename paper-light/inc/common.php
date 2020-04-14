<?php

$DBHOST = '137.189.99.13';
$DBNAME = 'mmlabweb';
$DBUSER = 'mmlabweb';
$DBPASS = 'gepai5Xo';

$TYPES = array(
    'article' => array(
        'required' => array('author', 'title', 'journal', 'year'),
        'optional' => array('volume', 'number', 'pages', 'month', 'note')
    ),
    'book' => array(
        'required' => array('author', 'title', 'publisher', 'year'),
        'optional' => array('volumn', 'series', 'address', 'edition', 'month', 'note')
    ),
    'booklet' => array(
        'required' => array('title'),
        'optional' => array('author', 'howpublished', 'address', 'month', 'year', 'note')
    ),
    'conference' => array(
        'required' => array('author', 'title', 'booktitle', 'year'),
        'optional' => array('editor', 'volume', 'series', 'pages', 'address', 'month', 'organization', 'publisher', 'note')
    ),
    'inbook' => array(
        'required' => array('author', 'title', 'pages', 'publisher', 'year'),
        'optional' => array('volume', 'series', 'type', 'address', 'edition', 'month', 'note')
    ),
    'incollection' => array(
        'required' => array('author', 'title', 'booktitle', 'publisher', 'year'),
        'optional' => array( 'editor', 'volume', 'series', 'type', 'chapter', 'pages', 'address', 'edition', 'month', 'note')
    ),
    'inproceedings' => array(
        'required' => array('author', 'title', 'booktitle', 'year'),
        'optional' => array('editor', 'volume', 'series', 'pages', 'address', 'month', 'organization', 'publisher', 'note')
    ),
    'manual' => array(
        'required' => array('title'),
        'optional' => array('author', 'organization', 'address', 'editor', 'month', 'year', 'note')
    ),
    'mastersthesis' => array(
        'required' => array('author', 'title', 'school', 'year'),
        'optional' => array('type', 'address', 'month', 'note')
    ),
    'misc' => array(
        'required' => array(),
        'optional' => array('author', 'title', 'howpublished', 'month', 'year', 'note')
    ),
    'phdthesis' => array(
        'required' => array('author', 'title', 'school', 'year'),
        'optional' => array('type', 'address', 'month', 'note')
    ),
    'proceedings' => array(
        'required' => array('title', 'year'),
        'optional' => array('editor', 'volume', 'series', 'address', 'month', 'publisher', 'organization', 'note')
    ),
    'techreport' => array(
        'required' => array('author', 'title', 'institution', 'year'),
        'optional' => array('type', 'number', 'address', 'month', 'note')
    ),
    'unpublished' => array(
        'required' => array('author', 'title', 'note'),
        'optional' => array('month', 'year')
    )
);
