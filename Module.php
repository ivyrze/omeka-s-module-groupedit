<?php declare(strict_types=1);

namespace GroupEdit;

use Omeka\Module\AbstractModule;
use GroupEdit\Permissions\Acl;
use Laminas\Mvc\MvcEvent;

class Module extends AbstractModule
{
    const NAMESPACE = __NAMESPACE__;
    
    /**
     * Get this module's configuration array.
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * {@inheritDoc}
     * @see \Omeka\Module\AbstractModule::onBootstrap()
     */
    public function onBootstrap(MvcEvent $event): void
    {
        parent::onBootstrap($event);

        $this->addAclRoleAndRules();
    }

    /**
     * Add ACL role and rules for this module.
     */
    protected function addAclRoleAndRules(): void
    {
        /** @var \Omeka\Permissions\Acl $acl */
        $services = $this->getServiceLocator();
        $acl = $services->get('Omeka\Acl');

        if (!$acl->hasRole(Acl::ROLE_GROUP_EDITOR)) {
            $acl->addRole(Acl::ROLE_GROUP_EDITOR, Acl::ROLE_RESEARCHER);
        }
        $acl->addRoleLabel(Acl::ROLE_GROUP_EDITOR, 'Group Editor'); // @translate
    }
}

?>