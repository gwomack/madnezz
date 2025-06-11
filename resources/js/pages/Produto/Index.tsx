import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Link, useForm, router } from '@inertiajs/react';
import { toast } from 'react-toastify';
import React from 'react';

type Produto = {
    id: number;
    nome: string;
    preco: number;
    descricao?: string;
    foto?: string;
    categoria: string;
    created_at: Date;
    updated_at: Date;
    deleted_at?: Date;
};

type Categoria = {
    value: string;
    label: string;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Produtos',
        href: '/produtos',
    },
];

export default function Index({ produtos, categorias, filters }: { produtos: { data: Produto[] }, categorias: Categoria[], filters?: { nome?: string, preco?: number } }) {
    const [filterForm, setFilterForm] = React.useState({
        nome: filters?.nome || '',
        preco: filters?.preco || '',
        categoria: '',
    });
    const { post } = useForm({
        _method: 'DELETE',
    });
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Produtos" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="col-span-full">
                        <div className="flex justify-between items-center mb-4">
                        <div className="flex gap-4">
                            <input
                                type="text"
                                placeholder="Filtrar por nome"
                                className="px-3 py-2 border rounded-md"
                                value={filterForm.nome}
                                onChange={(e) => {
                                    setFilterForm(prev => ({ ...prev, nome: e.target.value }));
                                    router.get(
                                        route('produtos.index'),
                                        { nome: e.target.value, preco: filterForm.preco },
                                        { preserveState: true }
                                    );
                                }}
                            />
                            <input
                                type="number"
                                placeholder="Filtrar por preço"
                                className="px-3 py-2 border rounded-md"
                                value={filterForm.preco}
                                onChange={(e) => {
                                    setFilterForm(prev => ({ ...prev, preco: e.target.value }));
                                    router.get(
                                        route('produtos.index'),
                                        { nome: filterForm.nome, preco: e.target.value },
                                        { preserveState: true }
                                    );
                                }}
                            />
                            <select
                                className="px-3 py-2 border rounded-md"
                                value={filterForm.categoria}
                                onChange={(e) => {
                                    setFilterForm(prev => ({ ...prev, categoria: e.target.value }));
                                    router.get(
                                        route('produtos.index'),
                                        { nome: filterForm.nome, preco: filterForm.preco, categoria: e.target.value },
                                        { preserveState: true }
                                    );
                                }}
                            >
                                <option value="">Filtrar por categoria</option>
                                {categorias.map((categoria) => (
                                    <option key={categoria.value} value={categoria.value}>
                                        {categoria.label}
                                    </option>
                                ))}
                            </select>
                        </div>
                        <div className="flex justify-end">
                            <Button asChild>
                                <Link href={route('produtos.create')}>
                                    Criar
                                </Link>
                            </Button>
                        </div>
                    </div>

                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Nome</TableHead>
                                    <TableHead>Preço</TableHead>
                                    <TableHead>Descrição</TableHead>
                                    <TableHead>Foto</TableHead>
                                    <TableHead>Categoria</TableHead>
                                    <TableHead>Criado em</TableHead>
                                    <TableHead>Atualizado em</TableHead>
                                    {/* <TableHead>Deletado em</TableHead> */}
                                    <TableHead>Ações</TableHead>
                                </TableRow>
                            </TableHeader>
                            {Array.isArray(produtos.data) ? (
                                produtos.data.map((produto) => (
                                    <TableBody key={produto.id}>
                                        <TableRow>
                                            <TableCell>{produto.nome}</TableCell>
                                            <TableCell>{produto.preco}</TableCell>
                                            <TableCell>{produto.descricao}</TableCell>
                                            <TableCell>{produto.foto}</TableCell>
                                            <TableCell>{produto.categoria}</TableCell>
                                            <TableCell>{new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeStyle: 'short' }).format(new Date(produto.created_at)).replace(',', '')}</TableCell>
                                            <TableCell>{new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeStyle: 'short' }).format(new Date(produto.updated_at)).replace(',', '')}</TableCell>
                                            {/* <TableCell>{produto.deleted_at ? new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeStyle: 'short' }).format(new Date(produto.deleted_at)).replace(',', '') : null}</TableCell> */}
                                            <TableCell>
                                                <Button
                                                    variant="outline"
                                                    asChild
                                                >
                                                    <Link href={route(`produtos.edit`, produto.id)}>
                                                        Editar
                                                    </Link>
                                                </Button>
                                                <Button
                                                    variant="destructive"
                                                    asChild
                                                >
                                                    <Link onClick={(e) => {
                                                        e.preventDefault();
                                                        if (confirm('Tem certeza que deseja deletar este produto?')) {
                                                            post(route(`produtos.destroy`, produto.id), {
                                                                onSuccess: () => {
                                                                    toast.success('Produto deletado com sucesso');
                                                                },
                                                            });
                                                        }
                                                    }} href={route(`produtos.destroy`, produto.id)}>
                                                        Deletar
                                                    </Link>
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                ))
                            ) : (
                                <TableBody>
                                    <TableRow>
                                        <TableCell colSpan={7}>
                                            <p>Nenhum produto encontrado.</p>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            )}
                        </Table>
                    </div>  
                </div>
            </div>
        </AppLayout>
    );
}
