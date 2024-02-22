<?php declare(strict_types=1);

namespace GroupEdit;

use Omeka\Module\AbstractModule;
use GroupEdit\Permissions\Acl;
use Laminas\Mvc\MvcEvent;
use Laminas\Permissions\Acl\Acl as LaminasAcl;
use Omeka\Permissions\Assertion\OwnsEntityAssertion;
use Omeka\Permissions\Assertion\AssertionNegation;
use GroupEdit\Permissions\Assertion\HasResourceSitePermissionAssertion;

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
            $acl->addRole(Acl::ROLE_GROUP_EDITOR, Acl::ROLE_REVIEWER);
        }
        $acl->addRoleLabel(Acl::ROLE_GROUP_EDITOR, 'Group Editor'); // @translate
        $this->addGroupEditorRules($acl);
    }

    protected function addGroupEditorRules(LaminasAcl $acl)
    {
        // Permissions for items and item sets
        $acl->deny(
            Acl::ROLE_GROUP_EDITOR,
            [
                'Omeka\Entity\Item',
                'Omeka\Entity\ItemSet',
                'Omeka\Entity\Media'
            ],
            [
                'update'
            ],
            new AssertionNegation(
                new HasResourceSitePermissionAssertion()
            )
        );
        $acl->allow(
            Acl::ROLE_GROUP_EDITOR,
            [
                'Omeka\Entity\Item',
                'Omeka\Entity\ItemSet',
                'Omeka\Entity\Media'
            ],
            [
                'delete'
            ],
            new HasResourceSitePermissionAssertion()
        );

        // Permissions for modules
        $acl->allow(
            Acl::ROLE_GROUP_EDITOR,
            [
                'Omeka\Controller\Admin\Index'
            ],
            [
                'index',
                'browse'
            ]
        );

        $acl->allow(
            Acl::ROLE_GROUP_EDITOR,
            [
                'Omeka\Module\Manager', 'Omeka\Controller\Admin\Module'
            ],
            [
                'index',
                'browse'
            ]
        );

        $acl->allow(
            Acl::ROLE_GROUP_EDITOR,
            [
                'CSVImport\Controller\Index'
            ],
            [
                'index',
                'browse'
            ]
        );

        // Permissions for assets
        $acl->deny(
            Acl::ROLE_GROUP_EDITOR,
            [
                'Omeka\Entity\Asset'
            ],
            [
                'update'
            ],
            new AssertionNegation(
                new OwnsEntityAssertion
            )
        );
    }
}

?>