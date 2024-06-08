<script lang="ts">
    import { inertia } from '@inertiajs/svelte';
    import tabler from 'yesvelte/css/tabler.min.css?url';
    import type { User } from '../types';
    import { Avatar, El, Icon } from 'yesvelte';
    import { initials } from '$lib/utils';
    import i18n from '$lib/i18n';
    import LangSwitcher from '$lib/Components/LangSwitcher.svelte';
    import { Button, Willow } from '@wx/svelte-core';
    import DropdownC from '$lib/Components/DropdownC.svelte';

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
<Willow>
<El container tag="main">
    <El row tag="header" alignItems="center">
        <El col>
            <a href={ loc ?? "/"}>
                <img src="logo.jpg" alt="logo" width="64px" />
                <h1 style="margin-block-end: 0;display: inline-block;">{$i18n.t('server:home.slogan')}</h1>
            </a>
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
                    <!-- svelte-ignore a11y-click-events-have-key-events -->
                    <!-- svelte-ignore a11y-no-static-element-interactions -->
                    <DropdownC>
                        <span slot="visible">{$i18n.t('server:home.welcome')}, <Button>{user.name}</Button></span>
                        <Icon name="user" color="primary" /><a class="y-button-link y-button-color-primary" use:inertia href={`${loc}/user/profile`}>{$i18n.t('server:auth.userprofile')}</a>
                        <Icon name="user-off" color="primary" /><button class="y-button-link y-button-color-primary" use:inertia={{href: logoutUrl, method: 'post'}}>{$i18n.t('server:auth.logout')}</button>
                    </DropdownC>
                    <!-- svelte-ignore missing-declaration -->
                    {:else}
                    <!-- svelte-ignore a11y-click-events-have-key-events -->
                    <!-- svelte-ignore a11y-no-static-element-interactions -->
                    <DropdownC>
                        <svelte:fragment slot="visible"><Button>{$i18n.t('server:auth.user')}</Button></svelte:fragment>
                        <div><Icon name="user" color="primary" /><a class="y-button-link y-button-color-primary" use:inertia href={`${loc}/login`}>{$i18n.t('server:auth.login')}</a></div>
                        <div><Icon name="report-medical" color="primary" /><a class="y-button-link y-button-color-primary" use:inertia href={`${loc}/register`}>{$i18n.t('server:auth.register')}</a></div>
                    </DropdownC>
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
</Willow>
