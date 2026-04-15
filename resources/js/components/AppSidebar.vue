<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import { Sidebar, SidebarContent, SidebarHeader, SidebarMenu, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BadgePercentIcon, ConstructionIcon, LayoutGrid, MegaphoneIcon, NewspaperIcon, ScrollTextIcon, ShieldIcon, SquarePercentIcon } from 'lucide-vue-next';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Banners',
        href: '/banners',
        icon: MegaphoneIcon,
    },
    {
        title: 'Promotions',
        href: '/promotions',
        icon: ConstructionIcon,
    },
    {
        title: 'Coupons',
        href: '/coupons',
        icon: SquarePercentIcon,
    },
    {
        title: 'News',
        icon: NewspaperIcon,
        subMenu: [
            {
                title: 'News Categories',
                href: '/news-categories'
            },
            {
                title: 'News',
                href: '/news',
            },
            {
                title: 'News Videos',
                href: '/news-videos'
            },
        ]
    },
    {
        title: 'Exclusive offers',
        href: '/exclusive-offers',
        icon: BadgePercentIcon
    },
    {
        title: 'Seagm',
        icon: ShieldIcon,
        subMenu: [
            {
                title: 'Cards',
                href: '/card',
            },
            {
                title: 'Card Items',
                href: '/card-items',
            },
        ]
    },
    {
        title: 'Terms & Conditions',
        href: '/terms',
        icon: ScrollTextIcon,
    },
    {
        title: 'About Us',
        icon: ScrollTextIcon,
        subMenu: [
            {
                title: 'Home',
                subMenu: [
                    {
                        title: 'Hero Section',
                        href: '/home-hero',
                    },
                    {
                        title: 'Page',
                        href: '/home-page',
                    },
                    {
                        title: 'CMS',
                        href: '/about-cms',
                    },
                    {
                        title: 'Advantage',
                        href: '/advantage',
                    },
                    {
                        title: 'Brands',
                        href: '/brands',
                    },
                    {
                        title: 'Through Items',
                        href: '/through',
                    },
                    {
                        title: 'Milestone',
                        href: '/milestones',
                    }
                ]
            },
            {
                title: 'About',
                href: '/about-us',
            },
            {
                title: 'Vision',
                href: '/vision',
            },
            {
                title: 'Departments',
                href: '/departments',
            },
            {
                title: 'Platform',
                href: '/platform'
            },
            {
                title: 'Career',
                subMenu: [
                    {
                        title: 'Careers Heading',
                        href: '/careers',
                    },
                    {
                        title: 'Employee Benefits',
                        href: '/employee-benefits',
                    },
                    {
                        title: 'Join Us',
                        href: '/join-us',
                    }
                ]
            },
            {
                title: 'About Contact',
                href: '/about-contact'
            },
            {
                title: 'Socials',
                href: '/socials'
            }
        ]
    },
    {
        title: 'Support',
        href: '/user-guide-categories',
        icon: ScrollTextIcon
    }



];

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <div class="mt-1 group/sidebar-wrapper" data-collapsible="icon">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <Link href="dashboard">
                            <div class="flex gap-2">
                                <!-- <div class="flex aspect-square size-9 items-center justify-center rounded-md mt-3">
                                <img class="w-14" src="/assets/images/fabicon.png" alt="icon">
                            </div> -->
                                <div class="grid flex-1 text-left text-sm mt-4">
                                    <img class="w-28" src="/assets/images/logo.png" alt="">
                                </div>
                            </div>
                        </Link>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>
        </div>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <!-- <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter> -->
    </Sidebar>
    <slot />
</template>
