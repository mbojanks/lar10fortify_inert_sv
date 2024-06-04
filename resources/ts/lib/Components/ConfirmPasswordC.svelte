<script lang="ts">
    import {createEventDispatcher, tick} from 'svelte';
    import i18n from '$lib/i18n';
    import { Button, CardActions, CardFooter, FormInput, Modal, ModalBody } from 'yesvelte';
    import type { ConfirmPasswordErrors } from 'resources/ts/types';
    //import axios from 'axios';

    const dispatch = createEventDispatcher();

    export let title: string = $i18n.t('server:auth.pwdconfirm');
    export let content: string = $i18n.t('server:auth.pwdconfirmdesc');
    export let button: string = $i18n.t('server:auth.pwdconfirm');
    export let locale: string = '';

    $: loc = (locale ? locale + '.' : "");

    /** confirming password in progress */
    let confirmingPassword = false;
    let errors: ConfirmPasswordErrors = {};
    const form = {
        password: '',
        processing: false,
    };
    let passwordInput: HTMLInputElement|null = null;

    const startConfirmingPassword = () => {
    fetch(route(`${loc}password.confirmation`)).then(response => response.json()).then((data) => {
        if (data.confirmed) {
            dispatch('confirmed');
        } else {
            confirmingPassword = true;
            //setTimeout(() => passwordInput.value.focus(), 250);
        }
    });
};

    const confirmPassword = () => {
        form.processing = true;
        /*window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';*/
        window.axios.post(route(`${loc}password.confirm`), {
        password: form.password,
    }).then(() => {
        form.processing = false;
        closeModal();
        tick().then(() => dispatch('confirmed'));

    }).catch((error: any) => {
        form.processing = false;
        errors = error.response.data.errors;
        //passwordInput.value.focus();
    });

    /** axios in Laravel can handle CSRF token!! */
        /*router.post(route(`${loc}password.confirm`), {
                password: form.password,
                _token: $page.props.csrf_token,
            }, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    form.processing = false;
                    closeModal();
                    tick().then(() => dispatch('confirmed'));
                },
                onError: (errs: any) => {
                    errors = errs;
                    form.processing = false;
                },
            });*/
        /*fetch(route(`${loc}password.confirm`), {
            body: JSON.stringify({
                password: form.password,
            }),
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            method: 'POST'
        }).then(() => {
            form.processing = false;
            closeModal();
            tick().then(() => dispatch('confirmed'));
        }).catch((error) => {
            form.processing = false;
            errors = error.errors;
            //passwordInput?.focus();
        }); */

    };

    const closeModal = () => {
        confirmingPassword = false;
        form.password = '';
        errors = {};
    };

</script>
<!-- svelte-ignore a11y-click-events-have-key-events -->
<!-- svelte-ignore a11y-no-static-element-interactions -->
<span on:click={startConfirmingPassword} style="display:inline-block;margin: 15px 0;">
    <slot />
</span>
<Modal {title} bind:show={confirmingPassword} dismissible >
	<ModalBody>
		{content}
        <FormInput label={$i18n.t('server:auth.passwordonform')} type="password" placeholder={$i18n.t('server:auth.passwordonform')} name="password" bind:value={form.password} state={errors.password ? 'invalid' : 'valid'} hint={errors.password}></FormInput>
	</ModalBody>
    <CardFooter>
        <CardActions d="flex" gap="2">
            <Button color="secondary" on:click={closeModal}>{$i18n.t('server:auth.pwdconfirmcancel')}</Button>
            <Button color="primary" disabled={form.processing} on:click={confirmPassword}>{button}</Button>
        </CardActions>
    </CardFooter>
</Modal>
