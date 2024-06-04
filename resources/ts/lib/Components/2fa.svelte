<script lang="ts">
    import i18n from "$lib/i18n";
    import { Button, Card, CardActions, CardBody, CardFooter, CardHeader, CardTitle, El, FormInput, Modal, ModalBody, ModalFooter } from "yesvelte";
    import { router, useForm, page } from '@inertiajs/svelte';
    import ConfirmPasswordC from "./ConfirmPasswordC.svelte";
    let axios = window.axios;

    export let requiresConfirmation: boolean = true;
    export let locale: string|undefined = undefined;
    export let title: string | undefined = undefined;
    export let description: string | undefined = undefined;
    $: loc = (locale ? locale + "." : "");

    let confirming: boolean = false;
    let enabling: boolean = false;
    let disabling: boolean = false;
    let qrCode: HTMLOrSVGElement|null = null;
    let setupKey: string|null = null;
    let recoveryCodes: string[] = [];

    let confirmationForm = useForm({
        code: ''
    });

    const enableTwoFactorAuthentication = () => {
        enabling = true;
        router.post(route(`${loc}two-factor.enable`), {}, {
            preserveScroll: true,
            onSuccess: () => Promise.all([
                showQrCode(),
                showSetupKey(),
                showRecoveryCodes(),
            ]),
            onFinish: () => {
                enabling = false;
                confirming = requiresConfirmation;
            },
        });
    };

    const showQrCode = () => {
        return axios.get(route(`${loc}two-factor.qr-code`)).then((response) => {
            qrCode = response.data.svg;
        });
    };

    const showSetupKey = () => {
        return axios.get(route(`${loc}two-factor.secret-key`)).then((response) => {
            setupKey = response.data.secretKey;
        });
    }

    const showRecoveryCodes = () => {
        return axios.get(route(`${loc}two-factor.recovery-codes`)).then((response) => {
            recoveryCodes = response.data;
        });
    };

    const confirmTwoFactorAuthentication = () => {
        console.log('in Confirm2FA: twoFactorEnabled', twoFactorEnabled, 'confirming', confirming, 'enabling', enabling, 'disabling', disabling);
        $confirmationForm.post(route(`${loc}two-factor.confirm`), {
            errorBag: "confirmTwoFactorAuthentication",
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                confirming = false;
                qrCode = null;
                setupKey = null;
            },
        });
    };

    /*const onKeyUp = (ev: CustomEvent<any>) => {
        let event = ev as unknown as KeyboardEvent;
        if (event.key === "Enter") {
            confirmTwoFactorAuthentication();
        }
    };*/

    const regenerateRecoveryCodes = () => {
        axios.post(route(`${loc}two-factor.recovery-codes`)).then(() => showRecoveryCodes());
    };

    const disableTwoFactorAuthentication = () => {
        disabling = true;

        router.delete(route(`${loc}two-factor.disable`), {
            preserveScroll: true,
            onSuccess: () => {
                disabling = false;
                confirming = false;
            },
        });
    };

    let twoFactorEnabled: boolean;
    $: twoFactorEnabled = !enabling && $page.props.user?.two_factor_enabled;
    $: if (!twoFactorEnabled) {
        $confirmationForm.reset();
        $confirmationForm.clearErrors();
    }

</script>
<Card mb="3">
    <CardHeader>
        <CardTitle>{title}</CardTitle>
    </CardHeader>
    <p>{description}</p>
        <CardBody>
            {#if twoFactorEnabled && ! confirming}
                <El tag="h2">{$i18n.t('server:auth.2FAenabled')}</El>
            {:else if twoFactorEnabled && confirming}
                <El tag="h2">{$i18n.t('server:auth.2FAenablefinish')}</El>
            {:else}
                <El tag="h2">{$i18n.t('server:auth.2FAenablednot')}</El>
            {/if}
            <El>{$i18n.t('server:auth.2FAenableddesc')}</El>
            {#if twoFactorEnabled}
                {#if qrCode}
                    {#if confirming}
                        <El>{$i18n.t('server:auth.2FAenabledfinishdesc')}</El>
                    {:else}
                        <El>{$i18n.t('server:auth.2FAenabledfinisheddesc')}</El>
                    {/if}
                    {@html qrCode}
                    {#if setupKey}
                        <El>{$i18n.t('server:auth.2FAsetupKey')}: <span>{@html setupKey}</span></El>
                    {/if}
                    {#if confirming}
                        <FormInput label={$i18n.t('server:auth.2FAcode')} name="code" type="number" bind:value={$confirmationForm.code} state={$confirmationForm.errors.code ? 'invalid' : 'valid'} hint={$confirmationForm.errors.code}></FormInput>
                    {/if}
                {/if}
                {#if recoveryCodes.length > 0 && !confirming}
                    <El>{$i18n.t('server:auth.2FArecoveryCodes')}</El>
                    <El border="2" borderRoundSize="3" p="3" w="auto" container>
                        {#each recoveryCodes as code}
                        <El row justifyContent="center">
                            <El pt="2" col="auto" fontWeight="bold" fontStyle="italic" alignSelf="center">{code}</El>
                        </El>
                        {/each}
                    </El>
                {/if}
            {/if}
            {#if !twoFactorEnabled}
                <ConfirmPasswordC {locale} on:confirmed={enableTwoFactorAuthentication}><Button color="primary" disabled={enabling}>{$i18n.t('server:auth.2FAenable')}</Button></ConfirmPasswordC>
            {:else}
                <ConfirmPasswordC {locale} on:confirmed={confirmTwoFactorAuthentication}>{#if confirming}<Button color="primary" disabled={enabling}>{$i18n.t('server:auth.2FAconfirm')}</Button>{/if}</ConfirmPasswordC>
                <ConfirmPasswordC {locale} on:confirmed={regenerateRecoveryCodes}>{#if recoveryCodes.length > 0 && !confirming}<Button color="secondary">{$i18n.t('server:auth.2FAregenerateRecoveryCodes')}</Button>{/if}</ConfirmPasswordC>
                <ConfirmPasswordC {locale} on:confirmed={showRecoveryCodes}>{#if recoveryCodes.length === 0 && !confirming}<Button color="secondary">{$i18n.t('server:auth.2FAshowRecoveryCodes')}</Button>{/if}</ConfirmPasswordC>
                <ConfirmPasswordC {locale} on:confirmed={disableTwoFactorAuthentication}>{#if confirming}<Button color="secondary" disabled={disabling}>{$i18n.t('server:auth.2FAcancel')}</Button>{/if}</ConfirmPasswordC>
                <ConfirmPasswordC {locale} on:confirmed={disableTwoFactorAuthentication}>{#if !confirming}<Button color="danger" disabled={disabling}>{$i18n.t('server:auth.2FAdisable')}</Button>{/if}</ConfirmPasswordC>
            {/if}
        </CardBody>
</Card>

