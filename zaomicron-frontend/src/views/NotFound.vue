<template>
  <v-container>
    <v-row>
      <v-col>
        <div>
        <h1>404 – Страница не найдена</h1>
        <v-btn color="secondary" to="/">На главную</v-btn>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<style>
@media (max-width: 992px) {
	.infobar_cookie {
		width: 80%;
	}
}

@media (min-width: 992px) {
	.infobar_cookie {
		width: 58%;
	}
}

.infobar_cookie {
	position: fixed;
	margin: auto;
	right: 0;
	bottom: 20px;
	left: 0;
	z-index: 1030;
	padding: 20px;
	background: rgba(0, 0, 0, .85);
	transition: bottom 0.8s ease, opacity 0.8s ease;
	color: #bebebe;
	font-size: 12px;
}
#oct-infobar-btn2 {
	font-size: 16px;
	margin-left: 20px;
	padding: 5px;
}
.infobar-text2 a {
	color: white;
	text-decoration: underline;
}
</style>
