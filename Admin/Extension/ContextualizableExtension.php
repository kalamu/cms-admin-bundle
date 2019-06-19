<?php

namespace Kalamu\CmsAdminBundle\Admin\Extension;

use Kalamu\CmsCoreBundle\Manager\ContentTypeManager;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class ContextualizableExtension extends AbstractAdminExtension
{

    protected $contentType;

    public function __construct(ContentTypeManager $contentType) {
        $this->contentType = $contentType;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $admin = $datagridMapper->getAdmin();
        $contentManager = $this->contentType->getManagerForClass($admin->getClass());

        if($contentManager->getContexts()){
            $datagridMapper->add('context_publication');
        }
    }
}