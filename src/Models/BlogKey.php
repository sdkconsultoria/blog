<?php

namespace Sdkconsultoria\Blog\Models;

use Sdkconsultoria\Base\Models\ResourceModel;

class BlogKey extends ResourceModel
{

    public function save(array $options = [])
    {
        $this->generateSeoname();
        $this->generateSeoname('value', 'seovalue');
        parent::save($options);
    }
}
