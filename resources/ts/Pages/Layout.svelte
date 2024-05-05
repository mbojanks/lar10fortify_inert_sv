<script lang="ts">
    import { inertia } from '@inertiajs/svelte';
    import tabler from 'yesvelte/css/tabler.min.css?url';
    import type { User } from '../types';
    import { Avatar, Button, Dropdown, DropdownItem, DropdownMenu, El, Icon } from 'yesvelte';
    import { initials } from '$lib/utils';
    import i18n from '$lib/i18n';
    import LangSwitcher from '$lib/Components/LangSwitcher.svelte';

    export let user: User;
    export let locales: string[];
    export let locale: string = '';
    $: loc = (locale ? "/" + locale : "");
    $: logoutUrl = route(locale + ".logout");
    $: $i18n.changeLanguage(locale);
</script>
<svelte:head>
    <link rel='stylesheet' href={tabler}/>
</svelte:head>
<El container tag="main">
    <El row tag="header" alignItems="center">
        <El col>
            <h1 style="margin-block-end: 0;">{$i18n.t('server:home.slogan')}</h1>
        </El>
        <El col>
            <El row rowCols="auto" justifyContent="center">
                <a class="y-button y-button-link y-button-color-primary" use:inertia href={ loc ?? "/"}>{$i18n.t('server:home.home')}</a>
                <a class="y-button y-button-link y-button-color-primary" use:inertia href={loc + "/contact"}>{$i18n.t('server:home.contact')}</a>
            </El>
        </El>
        <El col>
            <El row rowCols="auto" justifyContent="end" alignItems="end">
                <El col>
                    {#if user}
                    <Avatar>
                        {#if user.userimg}
                            <img alt="userimg" src={user.userimg.toString()} />
                        {:else}
                            <span>{initials(user.name)}</span>
                        {/if}
                    </Avatar>
                    <span>{$i18n.t('server:home.welcome')}, <a use:inertia href={`${loc}/user/profile-information`}>{user.name}</a></span>
                    <!-- svelte-ignore missing-declaration -->
                    <button class="y-button y-button-color-primary" use:inertia={{href: logoutUrl, method: 'post'}} type="button">{$i18n.t('server:auth.logout')}</button>
                    {:else}
                        <Dropdown>
                            <Button slot="target" link color="primary">{$i18n.t('server:auth.user')}</Button>
                            <DropdownMenu>
                                <DropdownItem>
                                    <Icon name="user" color="primary" /><a class="y-button-link y-button-color-primary" use:inertia href={`${loc}/login`}>{$i18n.t('server:auth.login')}</a>
                                </DropdownItem>
                                <DropdownItem>
                                    <Icon name="report-medical" color="primary" /><a class="y-button-link y-button-color-primary" use:inertia href={`${loc}/register`}>{$i18n.t('server:auth.register')}</a>
                                </DropdownItem>
                            </DropdownMenu>
                        </Dropdown>
                    {/if}
                </El>
                <El col>
                    <LangSwitcher {locale} {locales} fullreload />
                </El>
            </El>
        </El>
    </El>
    <El row tag="article">
        <slot />
    </El>
</El>
