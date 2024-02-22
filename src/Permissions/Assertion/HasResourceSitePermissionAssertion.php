<?php
namespace GroupEdit\Permissions\Assertion;

use Doctrine\Common\Collections\Criteria;
use Omeka\Entity\Site;
use Omeka\Entity\Media;
use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Assertion\AssertionInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;

class HasResourceSitePermissionAssertion implements AssertionInterface
{
    public function assert(Acl $acl, RoleInterface $role = null,
        ResourceInterface $resource = null, $privilege = null
    ) {
        // Unauthenticated users can't have any permissions
        if (!$role) {
            return false;
        }

        // Handle media based off of its parent item
        if ($resource instanceof Media) {
            $resource = $resource->getItem();
        }

        // Get array of associated sites
        if (method_exists($resource, 'getSites')) {
            $sites = $resource->getSites();
        } else if (method_exists($resource, 'getSiteItemSets')) {
            /** @var \Doctrine\Common\Collections\ArrayCollection $siteItemSets */
            $siteItemSets = $resource->getSiteItemSets();

            $sites = $siteItemSets->map(function ($siteItemSet) {
                /** @var \Omeka\Entity\SiteItemSet $siteItemSet */
                return $siteItemSet->getSite();
            });
        }

        if (empty($sites) || !$sites->first() instanceof Site) {
            return false;
        }

        // Check every associated site until we have permissions for one
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('user', $role));
        foreach ($sites as $site) {
            $sitePermission = $site->getSitePermissions()
                ->matching($criteria)->first();
            
            if (!is_bool($sitePermission)) {
                return true;
            }
        }
        
        return false;
    }
}

?>