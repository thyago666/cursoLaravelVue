<?

//MARCAS
public function up()
{
    Schema::create('marcas', function (Blueprint $table) {
        $table->id();
        $table->string('nome', 30)->unique();
        $table->string('imagem', 100)->comment('Logo da marca');
        $table->timestamps();
    });
}

//MODELOS
public function up()
{
    Schema::create('modelos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('marca_id');
        $table->string('nome', 30);
        $table->string('imagem', 100);
        $table->integer('numero_portas');
        $table->integer('lugares');
        $table->boolean('air_bag');
        $table->boolean('abs');
        $table->timestamps();

        //foreign key (constraints)
        $table->foreign('marca_id')->references('id')->on('marcas');
    });
}

//CARROS
public function up()
{
    Schema::create('carros', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('modelo_id');
        $table->string('placa', 10)->unique();
        $table->boolean('disponivel');
        $table->integer('km');
        $table->timestamps();

        //foreign key (constraints)
        $table->foreign('modelo_id')->references('id')->on('modelos');
    });
}

//CLIENTES
public function up()
{
    Schema::create('clientes', function (Blueprint $table) {
        $table->id();
        $table->string('nome', 30);
        $table->timestamps();
    });
}

//LOCACOES
// Renomear a classe para CreateLocacoesTable
// Renomear a tabela para locacoes (ajustar o model)
public function up()
{
    Schema::create('locacoes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('cliente_id');
        $table->unsignedBigInteger('carro_id');
        $table->dateTime('data_inicio_periodo');
        $table->dateTime('data_final_previsto_periodo');
        $table->dateTime('data_final_realizado_periodo');
        $table->float('valor_diaria', 8,2);
        $table->integer('km_inicial');
        $table->integer('km_final');
        $table->timestamps();

        //foreign key (constraints)
        $table->foreign('cliente_id')->references('id')->on('clientes');
        $table->foreign('carro_id')->references('id')->on('carros');
    });
}