<script lang="ts">
    import { Dropdown, Willow } from '@wx/svelte-core';
    let popup: boolean;

    const cancel = () => {popup = false;};
</script>
<Willow>
<!-- svelte-ignore a11y-click-events-have-key-events -->
<!-- svelte-ignore a11y-no-static-element-interactions -->
<div class="dropdownc" on:click={() => (popup = true)}>
    <slot name="visible"></slot>
    {#if popup}
    <Dropdown {cancel}>
        <slot></slot>
    </Dropdown>
    {/if}
</div>
</Willow>
<style>
    .dropdownc {
        position: relative;
        width:auto;
    }
</style>
