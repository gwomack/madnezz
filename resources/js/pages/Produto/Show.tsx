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


const getBreadcrumbs = (): BreadcrumbItem[] => [
    {
        title: 'Produtos',
        href: '/produtos',
    },
    {
        title: 'Produto',
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

export default function Show({ produto }: { produto: Produto }) {
    
    
    return (
        <AppLayout breadcrumbs={getBreadcrumbs()}>
            <Head title={`Produto ${produto.nome}`} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="col-span-1">
                        <div>
                            <div className="space-y-5">
                                <div className="grid gap-2">
                                    <p><strong>Nome:</strong> {produto.nome}</p>
                                </div>
                                <div className="grid gap-2">
                                    <p><strong>Preço:</strong> {produto.preco}</p>
                                </div>
                                <div className="grid gap-2">
                                    <p><strong>Descrição:</strong> {produto.descricao}</p>
                                </div>
                                <div className="grid gap-2">
                                    <p><strong>Foto:</strong>
                                        <img src={`/storage/${produto.foto}`} alt={produto.nome} width={100} height={100} />
                                    </p>
                                </div>
                                <div className="grid gap-2">
                                    <p><strong>Categoria:</strong> {produto.categoria}</p>
                                </div>
                                <div className="grid gap-2">
                                    <p><strong>Criado em:</strong> {!produto.created_at}</p>
                                </div>
                                <div className="grid gap-2">
                                    <p><strong>Atualizado em:</strong> {!produto.updated_at}</p>
                                </div>
                                {/* <div className="grid gap-2">
                                    <p><strong>Deletado em:</strong> {!produto.deleted_at}</p>
                                </div> */}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
