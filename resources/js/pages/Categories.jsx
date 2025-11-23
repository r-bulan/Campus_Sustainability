import React, { useState } from 'react';
import { base44 } from '@/api/base44Client';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Plus, Trash2, FolderTree } from 'lucide-react';
import { motion } from 'framer-motion';

export default function Categories() {
  const [newCategoryName, setNewCategoryName] = useState('');
  const queryClient = useQueryClient();

  const { data: categories = [], isLoading } = useQuery({
    queryKey: ['categories'],
    queryFn: () => base44.entities.Category.list(),
  });

  const createMutation = useMutation({
    mutationFn: (data) => base44.entities.Category.create(data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['categories'] });
      setNewCategoryName('');
    },
  });

  const deleteMutation = useMutation({
    mutationFn: (id) => base44.entities.Category.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['categories'] });
    },
  });

  const handleCreate = (e) => {
    e.preventDefault();
    if (newCategoryName.trim()) {
      createMutation.mutate({ name: newCategoryName.trim() });
    }
  };

  const handleDelete = (category) => {
    if (confirm(`Delete category "${category.name}"?`)) {
      deleteMutation.mutate(category.id);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="bg-white border-b border-gray-200">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
          <motion.div initial={{ opacity: 0, y: -20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.5 }}>
            <h1 className="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Categories</h1>
            <p className="text-sm sm:text-base text-gray-500">Organize your sustainability initiatives</p>
          </motion.div>
        </div>
      </div>

      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <div className="mb-6 lg:mb-8 border border-gray-100 rounded-xl bg-white shadow-sm">
          <div className="p-4 border-b border-gray-100">
            <div className="text-lg font-semibold">Add New Category</div>
          </div>
          <div className="p-4">
            <form onSubmit={handleCreate} className="flex flex-col sm:flex-row gap-3">
              <Input
                value={newCategoryName}
                onChange={(e) => setNewCategoryName(e.target.value)}
                placeholder="Enter category name"
                className="border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 flex-1"
              />
              <Button
                type="submit"
                disabled={!newCategoryName.trim() || createMutation.isLoading}
                className="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white w-full sm:w-auto"
              >
                <Plus className="w-4 h-4 mr-2" />
                Add
              </Button>
            </form>
          </div>
        </div>

        {isLoading ? (
          <div className="text-center py-12">
            <div className="animate-spin w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full mx-auto"></div>
          </div>
        ) : categories.length === 0 ? (
          <div className="bg-white rounded-2xl p-12 text-center border border-gray-100">
            <div className="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
              <FolderTree className="w-8 h-8 text-emerald-600" />
            </div>
            <h3 className="text-lg font-semibold text-gray-900 mb-2">No categories yet</h3>
            <p className="text-gray-500">Create your first category to get started</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
            {categories.map((category, index) => (
              <motion.div
                key={category.id}
                initial={{ opacity: 0, scale: 0.98 }}
                animate={{ opacity: 1, scale: 1 }}
                transition={{ duration: 0.25, delay: index * 0.03 }}
              >
                <div className="group bg-white rounded-lg border border-gray-100 p-4 hover:shadow transition-shadow">
                  <div className="flex items-center justify-between">
                    <div className="flex items-center gap-3">
                      <div className="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <FolderTree className="w-5 h-5 text-emerald-600" />
                      </div>
                      <span className="font-medium text-gray-900">{category.name}</span>
                    </div>
                    <button
                      onClick={() => handleDelete(category)}
                      className="opacity-0 group-hover:opacity-100 transition-opacity p-1 rounded text-gray-600 hover:text-red-600 hover:bg-red-50"
                      aria-label="Delete category"
                    >
                      <Trash2 className="w-4 h-4" />
                    </button>
                  </div>
                </div>
              </motion.div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
