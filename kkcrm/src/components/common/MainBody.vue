<template>
	<div class="main-body">
			<slot></slot>
	</div>
</template>
<script>
</script>
<style scoped>
	.main-body{
		width: 100%;
    padding: 10px;
    box-sizing: border-box;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    background-color: #fff;
    box-shadow: 0 2px 4px 0 rgb(54 58 80 / 32%);
    margin:20px auto 0 auto;
    box-sizing: border-box;
    border-radius: 0;
    color: rgba(0,0,0,.9);
	}
</style>
