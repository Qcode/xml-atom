<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

require_once 'XML/Atom/Element.php';
require_once 'XML/Atom/Author.php';
require_once 'XML/Atom/Category.php';
require_once 'XML/Atom/Contributor.php';
require_once 'XML/Atom/Generator.php';
require_once 'XML/Atom/Link.php';
require_once 'XML/Atom/Subtitle.php';
require_once 'XML/Atom/Title.php';
require_once 'XML/Atom/Updated.php';
require_once 'Date.php';

class XML_Atom_Source extends XML_Atom_Element
{
    protected $_id = '';
    protected $_title = null;
    protected $_updated = null;
    protected $_subtitle = null;
    protected $_icon = '';
    protected $_logo = '';
    protected $_rights = '';
    protected $_generator = null;
    protected $_authors = array();
    protected $_contributors = array();
    protected $_categories = array();
    protected $_links = array();

    public function __construct($id, $title, $updated = null)
    {
        $this->setId($id);
        $this->setTitle($title);
        $this->setUpdated($updated);
    }

    public function setId($id)
    {
        $this->_id = strval($id);
    }

    public function setTitle($title, $type = 'text')
    {
        if (!($title instanceof XML_Atom_Title)) {
            $title = new XML_Atom_Title($title, $type);
        }

        $this->_title = $title;
    }

    public function setUpdated($updated)
    {
        if (!($updated instanceof XML_Atom_Updated)) {
            $updated = new XML_Atom_Updated($updated);
        }

        $this->_updated = $updated;
    }

    public function setSubtitle($subtitle, $type = 'text')
    {
        if (!($subtitle === null || $subtitle instanceof XML_Atom_Subtitle)) {
            $subtitle = new XML_Atom_Subtitle($subtitle, $type);
        }

        $this->_subtitle = $subtitle;
    }

    public function setIcon($icon)
    {
        $this->_icon = strval($icon);
    }

    public function setLogo($logo)
    {
        $this->_logo = strval($logo);
    }

    public function setRights($rights)
    {
        $this->_rights = strval($rights);
    }

    public function setGenerator($generator, $uri = '', $version = '')
    {
        if (!($generator === null ||
            $generator instanceof XML_Atom_Generator)) {
            $generator = new XML_Atom_Generator($generator, $uri, $version);
        }

        $this->_generator = $generator;
    }

    public function addAuthor($name, $uri = '', $email = '')
    {
        if ($name instanceof XML_Atom_Author) {
            $author = $name;
        } else {
            $author = new XML_Atom_Author($name, $uri, $email);
        }

        $this->_authors[] = $author;
    }

    public function addContributor($name, $uri = '', $email = '')
    {
        if ($name instanceof XML_Atom_Contributor) {
            $contributor = $name;
        } else {
            $contributor = new XML_Atom_Contributor($name, $uri, $email);
        }

        $this->_contributors[] = $contributor;
    }

    public function addCategory($term, $scheme = '', $label = '',
        $language = '')
    {
        if ($term instanceof XML_Atom_Category) {
            $category = $term;
        } else {
            $category = new XML_Atom_Category($term, $scheme, $label,
                $language);
        }

        $this->_categories[] = $category;
    }

    public function addLink(XML_Atom_Link $link)
    {
        $this->_links[] = $link;
    }

    protected function _createNode(DOMDocument $document)
    {
        return $document->createElement('source');
    }

    protected function _buildNode(DOMNode $node)
    {
        $document = $node->ownerDocument;

        foreach ($this->_authors as $author) {
            $node->appendChild($author->_getNode($document));
        }

        foreach ($this->_categories as $category) {
            $node->appendChild($category->_getNode($document));
        }

        foreach ($this->_contributors as $contributor) {
            $node->appendChild($contributor->_getNode($document));
        }

        if ($this->_generator instanceof XML_Atom_Generator) {
            $node->appendChild($this->_generator->_getNode($document));
        }

        if ($this->_icon != '') {
            $icon_text_node = $document->createTextNode($this->_icon);
            $icon_node = $document->createElement('icon');
            $icon_node->appendChild($icon_text_node);
            $node->appendChild($icon_node);
        }

        $id_text_node = $document->createTextNode($this->_id);
        $id_node = $document->createElement('id');
        $id_node->appendChild($id_text_node);
        $node->appendChild($id_node);

        foreach ($this->_links as $link) {
            $node->appendChild($link->_getNode($document));
        }

        if ($this->_logo != '') {
            $logo_text_node = $document->createTextNode($this->_logo);
            $logo_node = $document->createElement('logo');
            $logo_node->appendChild($logo_text_node);
            $node->appendChild($logo_node);
        }

        if ($this->_rights != '') {
            $rights_text_node = $document->createTextNode($this->_rights);
            $rights_node = $document->createElement('rights');
            $rights_node->appendChild($rights_text_node);
            $node->appendChild($rights_node);
        }

        if ($this->_subtitle instanceof XML_Atom_Subtitle) {
            $node->appendChild($this->_subtitle->_getNode($document));
        }

        $node->appendChild($this->_title->_getNode($document));
        $node->appendChild($this->_updated->_getNode($document));
    }
}

?>
