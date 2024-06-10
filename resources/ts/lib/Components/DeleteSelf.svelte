<script lang="ts">
    import { Button, El, Fieldset, FormInput, Icon, Modal, ModalBody, ModalFooter } from "yesvelte";
    import ConfirmPasswordC from "./ConfirmPasswordC.svelte";
    import i18n from "$lib/i18n";
    import { useForm, page } from "@inertiajs/svelte";
    import type { ConfirmPasswordErrors } from "resources/ts/types";

    export let locale: string = '';
    $: loc = (locale ? locale + "." : "");

    export let errors: ConfirmPasswordErrors | undefined;

    let show: boolean = false;

    const onConfirmPwd = () => {
        show = true;
    };

    $: if (!show) $confPwd.clearErrors();

    const confPwd = useForm({
        password: ''
    });

    const onConfirmDelete = () => {
        $confPwd.delete(route(`${loc}current-user.destroy`));
    };
</script>
<h4>{$i18n.t('server:auth.accountdelete')}</h4>
<p>{$i18n.t('server:auth.accountdeleteDescription')}</p>
<Button color="pinterest" on:click={onConfirmPwd}>{$i18n.t('server:auth.accountdelete')}</Button>
<Modal bind:show dismissible size="sm">
	<ModalBody>
		<El textAlign="center">
			<Icon size="3x" color="danger" name="alert-triangle" />
			<El tag="h3">{$i18n.t('server:auth.sureAboutSelfDelete')}</El>
			<El textMuted>{$i18n.t('server:auth.sureAboutSelfDeleteDesc')}</El>
            <form>
                <Fieldset>
                    <FormInput type="password" name="password" label={$i18n.t('server:auth.passwordonform')} colMd="auto" bind:value={$confPwd.password} state={errors ? 'invalid' : 'valid'} hint={errors?.password} />
                </Fieldset>
            </form>
		</El>
	</ModalBody>
	<ModalFooter row>
		<Button col on:click={ () => {show = false;/*$confPwd.clearErrors();$page.props.errors = $confPwd.errors;*/ }}>{$i18n.t('server:auth.pwdconfirmcancel')}</Button>
		<Button col color="danger" on:click={onConfirmDelete} disabled={$confPwd.processing}>{$i18n.t('server:auth.sureAboutSelfDeleteDo')}</Button>
	</ModalFooter>
</Modal>
