import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useForm } from '@inertiajs/react';
import { Input } from '@/components/ui/input';
import { Textarea } from "@/components/ui/textarea"
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { LoaderCircle } from 'lucide-react';


const getBreadcrumbs = (isEditing: boolean): BreadcrumbItem[] => [
    {
        title: 'Produtos',
        href: '/produtos',
    },
    {
        title: isEditing ? 'Editar' : 'Criar',
        href: '#',
    },
];

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

export default function Edit({ produto, categorias }: { produto?: Produto, categorias: { value: string, label: string }[] }) {
    const isEditing = !!produto;

    const { data, setData, post, processing, errors, reset } = useForm<Required<{ nome: string, preco: number, descricao: string, foto: string, categoria: string }>>(
        produto ? {
        nome: produto.nome,
        preco: produto.preco,
        descricao: produto.descricao || '',
        foto: produto.foto || '',
        categoria: produto.categoria,
    } : {
        nome: '',
        preco: 0,
        descricao: '',
        foto: '',
        categoria: '',
    });
    
    return (
        <AppLayout breadcrumbs={getBreadcrumbs(isEditing)}>
            <Head title={`${isEditing ? 'Editar' : 'Criar'} Produto`} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="col-span-1">
                        <form onSubmit={(e) => {
                            e.preventDefault();
                            if (isEditing) {
                                post(route('produtos.update', produto!.id));
                            } else {
                                post(route('produtos.store'));
                            }
                        }}>
                            <div className="space-y-5">
                                <div className="grid gap-2">
                                    <Label htmlFor="nome">Nome</Label>
                                    <Input
                                        id="nome"
                                        type="text"
                                        name="nome"
                                        value={data.nome}
                                        onChange={(e) => setData('nome', e.target.value)}
                                        placeholder="Nome do produto"
                                    />
                                    {errors.nome && (
                                        <p className="text-red-500 text-sm mt-1">
                                            {errors.nome}
                                        </p>
                                    )}
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="preco">Preço</Label>
                                    <Input
                                        id="preco"
                                        type="number"
                                        step="0.50"
                                        min="0"
                                        name="preco"
                                        value={data.preco}
                                        onChange={(e) => setData('preco', Number(e.target.value))}
                                        placeholder="Preço do produto"
                                    />
                                    {errors.preco && (
                                        <p className="text-red-500 text-sm mt-1">
                                            {errors.preco}
                                        </p>
                                    )}
                                </div>
                                <div className="grid gap-2"> 
                                    <Label htmlFor="descricao">Descrição</Label>
                                    <Textarea
                                        id="descricao"
                                        name="descricao"
                                        value={data.descricao}
                                        onChange={(e) => setData('descricao', e.target.value)}
                                        placeholder="Descrição do produto"
                                    />
                                    {errors.descricao && (
                                        <p className="text-red-500 text-sm mt-1">
                                            {errors.descricao}
                                        </p>
                                    )}
                                </div>
                                <div className="grid gap-2"> 
                                    <Label htmlFor="foto">Foto</Label>
                                    <Input
                                        id="foto"
                                        type="text"
                                        name="foto"
                                        value={data.foto}
                                        onChange={(e) => setData('foto', e.target.value)}
                                        placeholder="Foto do produto"
                                    />
                                    {errors.foto && (
                                        <p className="text-red-500 text-sm mt-1">
                                            {errors.foto}
                                        </p>
                                    )}
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="categoria">Categoria</Label>
                                    <select
                                        id="categoria"
                                        className="border-input focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive flex h-9 w-full items-center justify-between rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs transition-[color,box-shadow] outline-none focus-visible:ring-[3px]"
                                        name="categoria"
                                        value={data.categoria}
                                        onChange={(e) => setData('categoria', e.target.value)}
                                    >
                                        <option value="">Selecione uma categoria</option>
                                        {categorias.map((categoria) => (
                                            <option key={categoria.value} value={categoria.value}>
                                                {categoria.label}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.categoria && (
                                        <p className="text-red-500 text-sm mt-1">
                                            {errors.categoria}
                                        </p>
                                    )}
                                </div>
                            </div>
                            <div className="my-6 flex items-center justify-start">
                                <Button className="w-full" disabled={processing}>
                                    {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                                    {isEditing ? 'Salvar' : 'Criar'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
