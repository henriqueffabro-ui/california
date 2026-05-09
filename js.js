const input = require('readline-sync');
const nome = input.question(" Digite seu nome : ");
const idade = Number(input.question(" Digite sua idade : "));
const cidade = input.question(" Digite sua cidade : ");
console.log(`Ola! Meu nome e ${nome}, tenho ${idade} anos e sou de ${cidade}.`);