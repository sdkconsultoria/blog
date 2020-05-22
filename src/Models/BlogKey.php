<?php

namespace Sdkconsultoria\Blog\Models;

use Sdkconsultoria\Base\Models\ResourceModel;

class BlogKey extends ResourceModel
{

    public function save(array $options = [])
    {
        $this->generateSeoname('name', 'seoname', false);
        $this->generateSeoname('value', 'seovalue', false);
        parent::save($options);
    }
}
